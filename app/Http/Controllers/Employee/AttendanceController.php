<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    private function employee()
    {
        return Auth::user()->employee;
    }

    public function index(Request $request)
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $records = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->orderBy('date', 'desc')
            ->get();

        $today = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        // جدول دوام اليوم
        $todaySchedule = WorkSchedule::forDay(now());

        $stats = [
            'present'      => $records->whereIn('status', ['present','late'])->count(),
            'absent'       => $records->where('status', 'absent')->count(),
            'late'         => $records->where('status', 'late')->count(),
            'total_hours'  => $records->sum('worked_minutes'),
            'overtime_min' => $records->sum('overtime_minutes'),
        ];

        $hasLocation = Setting::hasLocation();
        $companyLat  = Setting::companyLat();
        $companyLng  = Setting::companyLng();
        $radius      = Setting::attendanceRadius();

        return view('employee.attendance.index', compact(
            'records', 'today', 'stats', 'month',
            'hasLocation', 'companyLat', 'companyLng', 'radius',
            'todaySchedule'
        ));
    }

    public function checkIn(Request $request)
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $this->validateLocation($request->lat, $request->lng);

        if (Attendance::where('employee_id', $employee->id)->whereDate('date', today())->exists()) {
            return back()->with('error', __('emp.already_checked_in'));
        }

        $distance = $this->calcDistance($request->lat, $request->lng);

        // التحقق من التأخير عبر جدول الدوام
        $schedule  = WorkSchedule::forDay(now());
        $lateMin   = $schedule ? $schedule->lateMinutes(now()) : 0;
        $status    = $lateMin > 0 ? 'late' : 'present';

        Attendance::create([
            'employee_id'       => $employee->id,
            'date'              => today(),
            'check_in'          => now(),
            'check_in_lat'      => $request->lat,
            'check_in_lng'      => $request->lng,
            'check_in_distance' => $distance,
            'late_minutes'      => $lateMin,
            'status'            => $status,
        ]);

        return back()->with('success', __('emp.checked_in_success'));
    }

    public function checkOut(Request $request)
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $this->validateLocation($request->lat, $request->lng);

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->firstOrFail();

        $distance = $this->calcDistance($request->lat, $request->lng);

        // حساب الأوفرتايم
        $schedule  = WorkSchedule::forDay(now());
        $otMin     = $schedule ? $schedule->overtimeMinutes(now()) : 0;

        $attendance->update([
            'check_out'          => now(),
            'check_out_lat'      => $request->lat,
            'check_out_lng'      => $request->lng,
            'check_out_distance' => $distance,
            'overtime_minutes'   => $otMin,
        ]);

        return back()->with('success', __('emp.checked_out_success'));
    }

    private function validateLocation(float $lat, float $lng): void
    {
        if (!Setting::hasLocation()) return;
        $distance = $this->calcDistance($lat, $lng);
        $radius   = Setting::attendanceRadius();
        if ($distance > $radius) {
            abort(422, __('emp.too_far', [
                'distance' => round($distance),
                'max'      => $radius,
            ]));
        }
    }

    private function calcDistance(float $lat, float $lng): float
    {
        return Attendance::distanceMeters(
            Setting::companyLat(), Setting::companyLng(),
            $lat, $lng
        );
    }
}
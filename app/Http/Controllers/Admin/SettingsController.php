<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $schedules = WorkSchedule::allOrdered();

        return view('admin.settings.index', [
            // Company
            'company_name'             => Setting::get('company_name', ''),
            // Location
            'company_lat'              => Setting::get('company_lat', ''),
            'company_lng'              => Setting::get('company_lng', ''),
            'attendance_radius'        => Setting::get('attendance_radius', 200),
            // Payroll rates
            'absence_deduction_type'   => Setting::get('absence_deduction_type', 'daily'),
            'absence_deduction_value'  => Setting::get('absence_deduction_value', 0),
            'late_deduction_per_minute'=> Setting::get('late_deduction_per_minute', 0),
            'overtime_rate_per_hour'   => Setting::get('overtime_rate_per_hour', 0),
            // Work schedule
            'schedules'                => $schedules,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'              => 'nullable|string|max:255',
            'company_lat'               => 'nullable|numeric|between:-90,90',
            'company_lng'               => 'nullable|numeric|between:-180,180',
            'attendance_radius'         => 'nullable|integer|min:50|max:5000',
            'absence_deduction_type'    => 'required|in:daily,percentage',
            'absence_deduction_value'   => 'nullable|numeric|min:0',
            'late_deduction_per_minute' => 'nullable|numeric|min:0',
            'overtime_rate_per_hour'    => 'nullable|numeric|min:0',
        ]);

        $keys = [
            'company_name',
            'company_lat',
            'company_lng',
            'attendance_radius',
            'absence_deduction_type',
            'absence_deduction_value',
            'late_deduction_per_minute',
            'overtime_rate_per_hour',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        return back()->with('success', __('admin.settings_saved'));
    }

    // ── حفظ جدول الدوام الأسبوعي ─────────────────────
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'schedule'                     => 'required|array',
            'schedule.*.day_of_week'       => 'required|integer|between:0,6',
            'schedule.*.is_working_day'    => 'nullable|boolean',
            'schedule.*.start_time'        => 'nullable|date_format:H:i',
            'schedule.*.end_time'          => 'nullable|date_format:H:i',
            'schedule.*.grace_minutes'     => 'nullable|integer|min:0|max:120',
        ]);

        foreach ($request->schedule as $day) {
            $isWorking = isset($day['is_working_day']) ? true : false;
            WorkSchedule::updateOrCreate(
                ['day_of_week' => $day['day_of_week']],
                [
                    'is_working_day' => $isWorking,
                    'start_time'     => $isWorking ? ($day['start_time'] ?? null) : null,
                    'end_time'       => $isWorking ? ($day['end_time'] ?? null) : null,
                    'grace_minutes'  => $day['grace_minutes'] ?? 0,
                ]
            );
        }

        WorkSchedule::clearCache();

        return back()->with('success', __('admin.schedule_saved'));
    }
}
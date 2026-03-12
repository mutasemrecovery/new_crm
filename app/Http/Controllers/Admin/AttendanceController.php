<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
 
class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date     = $request->get('date', today()->format('Y-m-d'));
        $month    = $request->get('month', now()->format('Y-m'));
        $empId    = $request->get('employee_id');
        $viewMode = $request->get('view', 'day'); // day | month
 
        $employees = Employee::where('status', 'active')->orderBy('name')->get();
 
        if ($viewMode === 'day') {
            $query = Attendance::with('employee')
                ->whereDate('date', $date);
            if ($empId) $query->where('employee_id', $empId);
            $records = $query->orderBy('check_in')->get();
 
            // موظفون غائبون اليوم
            $presentIds = $records->pluck('employee_id');
            $absentEmployees = $employees->whereNotIn('id', $presentIds);
 
            $stats = [
                'present'  => $records->whereIn('status', ['present','late'])->count(),
                'late'     => $records->where('status', 'late')->count(),
                'absent'   => $absentEmployees->count(),
                'total'    => $employees->count(),
            ];
 
            return view('admin.attendance.index', compact(
                'records', 'employees', 'absentEmployees',
                'stats', 'date', 'month', 'empId', 'viewMode'
            ));
        }
 
        // Month view
        [$year, $mon] = explode('-', $month);
        $query = Attendance::with('employee')
            ->whereYear('date', $year)
            ->whereMonth('date', $mon);
        if ($empId) $query->where('employee_id', $empId);
        $records = $query->orderBy('date', 'desc')->orderBy('employee_id')->get();
 
        $stats = [
            'present'  => $records->whereIn('status', ['present','late'])->count(),
            'late'     => $records->where('status', 'late')->count(),
            'absent'   => $records->where('status', 'absent')->count(),
            'avg_hours'=> $records->avg('worked_minutes') ? round($records->avg('worked_minutes') / 60, 1) : 0,
        ];
 
        return view('admin.attendance.index', compact(
            'records', 'employees', 'stats',
            'date', 'month', 'empId', 'viewMode'
        ));
    }
}
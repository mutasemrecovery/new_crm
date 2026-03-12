<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    private function employee()
    {
        return Auth::user()->employee;
    }

    public function index()
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $leaves  = Leave::where('employee_id', $employee->id)->latest()->paginate(15);
        $balance = LeaveBalance::getOrCreate($employee->id, now()->year);

        $stats = [
            'pending'   => Leave::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved'  => Leave::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'remaining' => $balance->remaining,
            'used'      => $balance->used_days,
        ];

        return view('employee.leaves.index', compact('leaves', 'balance', 'stats'));
    }

    public function create()
    {
        $employee = $this->employee();
        $balance  = LeaveBalance::getOrCreate($employee->id, now()->year);
        return view('employee.leaves.create', compact('balance'));
    }

    public function store(Request $request)
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $request->validate([
            'type'       => 'required|in:annual,sick,emergency,unpaid,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'nullable|string|max:1000',
        ]);

        $days = Leave::calcDays($request->start_date, $request->end_date);

        // تحقق من الرصيد إذا كانت سنوية
        if ($request->type === 'annual') {
            $balance = LeaveBalance::getOrCreate($employee->id, now()->year);
            if ($days > $balance->remaining) {
                return back()->withErrors(['end_date' => __('emp.insufficient_balance', [
                    'remaining' => $balance->remaining,
                    'requested' => $days,
                ])])->withInput();
            }
        }

        // تحقق من عدم وجود إجازة متداخلة
        $overlap = Leave::where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date',   [$request->start_date, $request->end_date])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_date', '<=', $request->start_date)
                         ->where('end_date',   '>=', $request->end_date);
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['start_date' => __('emp.leave_overlap')])->withInput();
        }

        Leave::create([
            'employee_id' => $employee->id,
            'type'        => $request->type,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'days_count'  => $days,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', __('emp.leave_submitted'));
    }

    public function destroy(Leave $leave)
    {
        $employee = $this->employee();
        if ($leave->employee_id !== $employee->id) abort(403);
        if (!$leave->isPending()) {
            return back()->with('error', __('emp.cannot_cancel_reviewed_leave'));
        }
        $leave->delete();
        return back()->with('success', __('emp.leave_cancelled'));
    }
}


<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee')->latest();

        if ($request->status)      $query->where('status', $request->status);
        if ($request->type)        $query->where('type', $request->type);
        if ($request->employee_id) $query->where('employee_id', $request->employee_id);

        $leaves    = $query->paginate(25)->withQueryString();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();

        $stats = [
            'pending'  => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
            'this_month' => Leave::whereMonth('start_date', now()->month)
                                 ->whereYear('start_date', now()->year)->count(),
        ];

        return view('admin.leaves.index', compact('leaves', 'employees', 'stats'));
    }

    public function show(Leave $leave)
    {
        $leave->load('employee', 'reviewer');
        $balance = LeaveBalance::getOrCreate($leave->employee_id, $leave->start_date->year);
        return view('admin.leaves.show', compact('leave', 'balance'));
    }

    public function approve(Request $request, Leave $leave)
    {
        if (!$leave->isPending()) {
            return back()->with('error', __('admin.leave_already_reviewed'));
        }

        $leave->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        // خصم من الرصيد إذا كانت سنوية
        if ($leave->type === 'annual') {
            $balance = LeaveBalance::getOrCreate($leave->employee_id, $leave->start_date->year);
            $balance->increment('used_days', $leave->days_count);
        }

        return back()->with('success', __('admin.leave_approved'));
    }

    public function reject(Request $request, Leave $leave)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        if (!$leave->isPending()) {
            return back()->with('error', __('admin.leave_already_reviewed'));
        }

        $leave->update([
            'status'      => 'rejected',
            'admin_note'  => $request->admin_note,
            'reviewed_by' => Auth::guard('admin')->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', __('admin.leave_rejected'));
    }

    // ── إدارة أرصدة الإجازات ──────────────────────────
    public function balances(Request $request)
    {
        $year      = $request->get('year', now()->year);
        $employees = Employee::where('status', 'active')->get()->map(function ($emp) use ($year) {
            $emp->balance = LeaveBalance::getOrCreate($emp->id, $year);
            return $emp;
        });

        return view('admin.leaves.balances', compact('employees', 'year'));
    }

    public function updateBalance(Request $request, Employee $employee)
    {
        $request->validate([
            'annual_balance' => 'required|integer|min:0|max:365',
            'year'           => 'required|integer|min:2020|max:2099',
        ]);

        $balance = LeaveBalance::getOrCreate($employee->id, $request->year);
        $balance->update(['annual_balance' => $request->annual_balance]);

        return back()->with('success', __('admin.balance_updated'));
    }
}
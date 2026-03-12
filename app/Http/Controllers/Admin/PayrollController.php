<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $payrolls  = Payroll::with('employee')
            ->where('year', $year)->where('month', $mon)
            ->orderBy('employee_id')->get();

        $employees = Employee::where('status', 'active')->get();

        // الموظفون الذين ليس لهم كشف راتب هذا الشهر
        $generatedIds  = $payrolls->pluck('employee_id');
        $missingEmployees = $employees->whereNotIn('id', $generatedIds);

        $stats = [
            'total_net'   => $payrolls->sum('net_salary'),
            'paid_count'  => $payrolls->where('status', 'paid')->count(),
            'draft_count' => $payrolls->where('status', 'draft')->count(),
            'total_count' => $payrolls->count(),
        ];

        return view('admin.payroll.index', compact(
            'payrolls', 'employees', 'missingEmployees', 'stats', 'month', 'year', 'mon'
        ));
    }

    // ── توليد كشف راتب لموظف واحد ─────────────────────
    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|date_format:Y-m',
        ]);

        [$year, $mon] = explode('-', $request->month);
        $employee = Employee::findOrFail($request->employee_id);

        // إذا موجود مسبقاً لا تعيد التوليد
        if (Payroll::where('employee_id', $employee->id)
                   ->where('year', $year)->where('month', $mon)->exists()) {
            return back()->with('error', __('admin.payroll_already_exists'));
        }

        $data = Payroll::buildFromAttendance($employee, (int)$year, (int)$mon);
        $data['employee_id'] = $employee->id;
        $data['year']        = $year;
        $data['month']       = $mon;
        $data['created_by']  = Auth::guard('admin')->id();

        Payroll::create($data);

        return back()->with('success', __('admin.payroll_generated', ['name' => $employee->name]));
    }

    // ── توليد للكل دفعة واحدة ─────────────────────────
    public function generateAll(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);
        [$year, $mon] = explode('-', $request->month);

        $employees = Employee::where('status', 'active')->get();
        $count = 0;

        foreach ($employees as $emp) {
            if (Payroll::where('employee_id', $emp->id)
                       ->where('year', $year)->where('month', $mon)->exists()) continue;

            $data = Payroll::buildFromAttendance($emp, (int)$year, (int)$mon);
            $data['employee_id'] = $emp->id;
            $data['year']        = $year;
            $data['month']       = $mon;
            $data['created_by']  = Auth::guard('admin')->id();
            Payroll::create($data);
            $count++;
        }

        return back()->with('success', __('admin.payroll_generated_all', ['count' => $count]));
    }

    // ── تعديل كشف راتب ────────────────────────────────
    public function edit(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('admin.payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'bonuses'               => 'nullable|numeric|min:0',
            'deduction_manual'      => 'nullable|numeric|min:0',
            'deduction_manual_note' => 'nullable|string|max:500',
            'notes'                 => 'nullable|string',
        ]);

        $payroll->bonuses              = $request->bonuses ?? 0;
        $payroll->deduction_manual     = $request->deduction_manual ?? 0;
        $payroll->deduction_manual_note= $request->deduction_manual_note;
        $payroll->notes                = $request->notes;
        $payroll->net_salary = round(
            $payroll->basic_salary
            + $payroll->commissions_amount
            + $payroll->bonuses
            - $payroll->deduction_absence
            - $payroll->deduction_late
            - $payroll->deduction_manual,
            2
        );
        $payroll->save();

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', __('admin.payroll_updated'));
    }

    // ── عرض + طباعة سليب ──────────────────────────────
    public function show(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('admin.payroll.show', compact('payroll'));
    }

    // ── تأكيد الدفع ───────────────────────────────────
    public function markPaid(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid', 'paid_at' => today()]);
        return back()->with('success', __('admin.payroll_paid'));
    }

    // ── حذف ───────────────────────────────────────────
    public function destroy(Payroll $payroll)
    {
        if ($payroll->isPaid()) {
            return back()->with('error', __('admin.cannot_delete_paid_payroll'));
        }
        $payroll->delete();
        return back()->with('success', __('admin.deleted_successfully', ['item' => __('admin.payroll')]));
    }
}
<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    private function employee()
    {
        return Auth::user()->employee;
    }

    public function index()
    {
        $employee = $this->employee();
        if (!$employee) abort(403);

        $payrolls = Payroll::where('employee_id', $employee->id)
            ->where('status', 'paid')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        $latest = $payrolls->first();

        return view('employee.payroll.index', compact('payrolls', 'latest'));
    }

    public function show(Payroll $payroll)
    {
        $employee = $this->employee();
        if ($payroll->employee_id !== $employee->id) abort(403);
        if ($payroll->isDraft()) abort(403, 'Payroll not released yet.');
        $payroll->load('employee');
        return view('employee.payroll.show', compact('payroll'));
    }
}
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SalesCommission;
use App\Models\Employee;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesCommission::with('employee.user','client',);
        if ($request->status)      $query->where('status',      $request->status);
        if ($request->employee_id) $query->where('employee_id', $request->employee_id);

        $commissions = $query->latest()->paginate(20)->withQueryString();
        $employees   = Employee::with('user')->where('is_sales',true)->get();
        $stats = [
            'total_pending' => SalesCommission::where('status','pending')->sum('amount'),
            'total_paid'    => SalesCommission::where('status','paid')->sum('amount'),
            'count_pending' => SalesCommission::where('status','pending')->count(),
        ];
        return view('admin.commissions.index', compact('commissions','employees','stats'));
    }

    public function pay(Employee $employee)
    {
        $count = SalesCommission::where('employee_id',$employee->id)
            ->where('status','pending')
            ->update(['status' => 'paid', 'paid_at' => now()]);

        return back()->with('success', "$count ".__('admin.commissions_paid'));
    }

    public function paySingle(SalesCommission $commission)
    {
        $commission->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', __('admin.commission_paid'));
    }
}
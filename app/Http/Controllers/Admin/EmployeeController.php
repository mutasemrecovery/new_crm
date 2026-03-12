<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user')->latest();

        if ($s = $request->search) {
            $query->where(fn($q) => $q
                ->where('name',      'like', "%$s%")
                ->orWhere('email',   'like', "%$s%")
                ->orWhere('phone',   'like', "%$s%")
                ->orWhere('job_title','like',"%$s%")
            );
        }
        if ($request->department) $query->where('department',  $request->department);
        if ($request->status)     $query->where('status',      $request->status);
        if ($request->is_sales)   $query->where('is_sales',    true);

        $employees = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => Employee::count(),
            'active'   => Employee::where('status', 'active')->count(),
            'vacation' => Employee::where('status', 'vacation')->count(),
            'sales'    => Employee::where('is_sales', true)->count(),
            'salary'   => Employee::where('status', 'active')->sum('salary'),
        ];

        return view('admin.employees.index', compact('employees', 'stats'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'job_title'         => 'required|string|max:255',
            'department'        => 'required|in:design,video,development,social_media,marketing,sales,accounting,management',
            'specializations'   => 'nullable|string',   // comma-separated → JSON
            'salary'            => 'nullable|numeric|min:0',
            'is_sales'          => 'nullable|boolean',
            'commission_rate'   => 'nullable|numeric|min:0|max:100',
            'commission_type'   => 'nullable|in:per_deal,monthly_percentage',
            'status'            => 'required|in:active,inactive,vacation',
            'hire_date'         => 'nullable|date',
            'notes'             => 'nullable|string',
            'avatar'            => 'nullable|image|max:2048',
            // user account (optional)
            'create_account'    => 'nullable|boolean',
            'user_phone'        => 'nullable|required_if:create_account,1|string|max:30|unique:users,phone',
            'user_password'     => 'nullable|required_if:create_account,1|string|min:6',
        ]);

        // handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // handle specializations: "Photoshop, Laravel" → ["Photoshop","Laravel"]
        $specializations = null;
        if (!empty($data['specializations'])) {
            $specializations = array_values(array_filter(
                array_map('trim', explode(',', $data['specializations']))
            ));
        }

        // create user account if requested
        $userId = null;
        if ($request->boolean('create_account')) {
            $user = User::create([
                'name'     => $data['name'],
                'phone'    => $data['user_phone'],
                'password' => Hash::make($data['user_password']),
                'activate' => 1,
            ]);
            $userId = $user->id;
        }

        Employee::create([
            'user_id'          => $userId,
            'name'             => $data['name'],
            'name_en'          => $data['name_en']          ?? null,
            'phone'            => $data['phone']            ?? null,
            'email'            => $data['email']            ?? null,
            'job_title'        => $data['job_title'],
            'department'       => $data['department'],
            'specializations'  => $specializations,
            'salary'           => $data['salary']           ?? 0,
            'is_sales'         => $request->boolean('is_sales'),
            'commission_rate'  => $data['commission_rate']  ?? 0,
            'commission_type'  => $data['commission_type']  ?? 'per_deal',
            'avatar'           => $avatarPath,
            'status'           => $data['status'],
            'hire_date'        => $data['hire_date']        ?? null,
            'notes'            => $data['notes']            ?? null,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', __('admin.created_successfully', ['item' => __('admin.employee')]));
    }

    public function show(Employee $employee)
    {
        $employee->load('user', 'commissions.contract.client');
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('admin.employees.create', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'name_en'           => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'job_title'         => 'required|string|max:255',
            'department'        => 'required|in:design,video,development,social_media,marketing,sales,accounting,management',
            'specializations'   => 'nullable|string',
            'salary'            => 'nullable|numeric|min:0',
            'is_sales'          => 'nullable|boolean',
            'commission_rate'   => 'nullable|numeric|min:0|max:100',
            'commission_type'   => 'nullable|in:per_deal,monthly_percentage',
            'status'            => 'required|in:active,inactive,vacation',
            'hire_date'         => 'nullable|date',
            'notes'             => 'nullable|string',
            'avatar'            => 'nullable|image|max:2048',
            // user account updates
            'user_phone'        => 'nullable|string|max:30|unique:users,phone,' . ($employee->user_id ?? 'NULL'),
            'user_password'     => 'nullable|string|min:6',
            'user_activate'     => 'nullable|boolean',
        ]);

        // avatar
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // specializations
        $specializations = null;
        if (!empty($data['specializations'])) {
            $specializations = array_values(array_filter(
                array_map('trim', explode(',', $data['specializations']))
            ));
        }

        // update linked user account
        if ($employee->user) {
            $userUpdate = [];
            if (!empty($data['user_phone']))    $userUpdate['phone']    = $data['user_phone'];
            if (!empty($data['user_password'])) $userUpdate['password'] = Hash::make($data['user_password']);
            if (isset($data['user_activate']))  $userUpdate['activate'] = $request->boolean('user_activate') ? 1 : 2;
            if ($userUpdate) $employee->user->update($userUpdate);
        }

        $employee->update([
            'name'            => $data['name'],
            'name_en'         => $data['name_en']         ?? null,
            'phone'           => $data['phone']           ?? null,
            'email'           => $data['email']           ?? null,
            'job_title'       => $data['job_title'],
            'department'      => $data['department'],
            'specializations' => $specializations,
            'salary'          => $data['salary']          ?? 0,
            'is_sales'        => $request->boolean('is_sales'),
            'commission_rate' => $data['commission_rate'] ?? 0,
            'commission_type' => $data['commission_type'] ?? 'per_deal',
            'avatar'          => $data['avatar']          ?? $employee->avatar,
            'status'          => $data['status'],
            'hire_date'       => $data['hire_date']       ?? null,
            'notes'           => $data['notes']           ?? null,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', __('admin.updated_successfully', ['item' => __('admin.employee')]));
    }

    public function destroy(Employee $employee)
    {
        if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', __('admin.deleted_successfully', ['item' => __('admin.employee')]));
    }
}
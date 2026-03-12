<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Client;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('client', 'service', 'employees');
        if ($s = $request->search) {
            $query->where(fn($q) => $q
                ->where('title', 'like', "%$s%")
                ->orWhereHas('client', fn($q2) => $q2->where('name', 'like', "%$s%")));
        }
        if ($request->status)    $query->where('status',    $request->status);
        if ($request->priority)  $query->where('priority',  $request->priority);
        if ($request->client_id) $query->where('client_id', $request->client_id);

        $tasks   = $query->latest()->paginate(50)->withQueryString();
        $clients = Client::orderBy('name')->get();
        $stats   = [
            'todo'        => Task::where('status', 'todo')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'review'      => Task::where('status', 'review')->count(),
            'done'        => Task::where('status', 'done')->count(),
        ];
        return view('admin.tasks.index', compact('tasks', 'clients', 'stats'));
    }

    public function create(Request $request)
    {
        $clients   = Client::where('status', 'active')->orderBy('name')->get();
        $services  = Service::where('is_active', true)->get();
        $employees = Employee::with('user')->where('status', 'active')->get();
        $preClient = $request->client_id ? Client::find($request->client_id) : null;
        return view('admin.tasks.create', compact('clients', 'services', 'employees', 'preClient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'required|exists:clients,id',
            'service_id'  => 'nullable|exists:services,id',
            'status'      => 'required|in:todo,in_progress,review,done,cancelled',
            'priority'    => 'required|in:urgent,high,medium,low',
            'due_date'    => 'nullable|date',
            'progress'    => 'nullable|integer|min:0|max:100',
            'notes'       => 'nullable|string',
        ]);

        $request->validate([
            'employees'   => 'nullable|array',
            'employees.*' => 'exists:employees,id',
        ]);

        $data['created_by'] = Auth::id();

        $task = Task::create($data);

        // log initial status
        \App\Models\TaskStatusLog::create([
            'task_id'      => $task->id,
            'from_status'  => null,
            'to_status'    => $task->status,
            'changed_by'   => Auth::guard('admin')->id(),
            'changer_type' => 'admin',
            'changer_name' => Auth::guard('admin')->user()->name ?? 'Admin',
            'note'         => 'تم إنشاء المهمة',
        ]);

        $task->employees()->sync($request->input('employees', []));

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', __('admin.created_successfully', ['item' => __('admin.task')]));
    }

    public function show(Task $task)
    {
        $task->load(
            'client', 'service', 'employees.user',
            'comments.user', 'comments.admin',
            'statusLogs',
            'creator'
        );
        $allEmployees = Employee::where('status', 'active')->get();
        return view('admin.tasks.show', compact('task', 'allEmployees'));
    }

    public function edit(Task $task)
    {
        $clients     = Client::orderBy('name')->get();
        $services    = Service::where('is_active', true)->get();
        $employees   = Employee::with('user')->where('status', 'active')->get();
        $assignedIds = $task->employees()->pluck('employees.id')->toArray();
        return view('admin.tasks.edit', compact('task', 'clients', 'services', 'employees', 'assignedIds'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'required|exists:clients,id',
            'service_id'  => 'nullable|exists:services,id',
            'status'      => 'required|in:todo,in_progress,review,done,cancelled',
            'priority'    => 'required|in:urgent,high,medium,low',
            'due_date'    => 'nullable|date',
            'progress'    => 'nullable|integer|min:0|max:100',
            'notes'       => 'nullable|string',
        ]);

        $request->validate([
            'employees'   => 'nullable|array',
            'employees.*' => 'exists:employees,id',
        ]);

        // log status change if changed
        if ($task->status !== $data['status']) {
            $admin = Auth::guard('admin')->user();
            $task->changeStatus(
                $data['status'],
                'admin',
                $admin->id,
                $admin->name ?? 'Admin'
            );
            unset($data['status']); // already updated inside changeStatus
        }

        $task->update($data);
        $task->employees()->sync($request->input('employees', []));

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', __('admin.updated_successfully', ['item' => __('admin.task')]));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')
            ->with('success', __('admin.deleted_successfully', ['item' => __('admin.task')]));
    }

    // ── تغيير الحالة السريع من صفحة Show ─────────────
    public function changeStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done,cancelled',
            'note'   => 'nullable|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();

        $task->changeStatus(
            $request->status,
            'admin',
            $admin->id,
            $admin->name ?? 'Admin',
            $request->note
        );

        return back()->with('success', __('admin.status_updated'));
    }

    // ── Assign موظف إضافي ─────────────────────────────
    public function assignEmployee(Request $request, Task $task)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        // attach إن لم يكن موجوداً
        if (!$task->employees()->where('employee_id', $request->employee_id)->exists()) {
            $task->employees()->attach($request->employee_id, [
                'assigned_at' => now(),
            ]);
        }

        return back()->with('success', __('admin.employee_assigned'));
    }

    // ── إزالة موظف من المهمة ──────────────────────────
    public function removeEmployee(Task $task, Employee $employee)
    {
        $task->employees()->detach($employee->id);
        return back()->with('success', __('admin.employee_removed'));
    }

    // ── تعليق الأدمن (يحفظ admin_id مش user_id) ──────
    public function storeComment(Request $request, Task $task)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $task->comments()->create([
            'user_id'  => null,
            'admin_id' => Auth::guard('admin')->id(),
            'body'     => $request->body,
        ]);

        return back()->with('success', __('admin.comment_added'));
    }

    public function markComplete(Task $task)
    {
        $admin = Auth::guard('admin')->user();
        $task->changeStatus('done', 'admin', $admin->id, $admin->name ?? 'Admin', 'تم القبول من المدير');
        $task->update(['progress' => 100]);
        return back()->with('success', __('admin.task_completed'));
    }
}
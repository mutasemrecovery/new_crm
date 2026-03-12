<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function currentEmployee()
    {
        return Auth::user()->employee;
    }

    private function authorizeTask(Task $task): Employee
    {
        $employee = $this->currentEmployee();
        if (!$employee || !$task->employees()->where('employee_id', $employee->id)->exists()) {
            abort(403);
        }
        return $employee;
    }

    public function index(Request $request)
    {
        $employee = $this->currentEmployee();

        if (!$employee) {
            return view('employee.tasks.index', [
                'tasks'      => collect(),
                'stats'      => ['todo'=>0,'in_progress'=>0,'review'=>0,'done'=>0],
                'noEmployee' => true,
            ]);
        }

        $query = $employee->tasks()->with('client', 'service', 'employees');

        if ($s = $request->search)  $query->where('title', 'like', "%$s%");
        if ($request->status)       $query->where('status',   $request->status);
        if ($request->priority)     $query->where('priority', $request->priority);

        $query->orderByRaw("FIELD(status,'in_progress','review','todo','done','cancelled')")
              ->orderBy('due_date', 'asc')
              ->orderBy('created_at', 'desc');

        $tasks = $query->paginate(20)->withQueryString();

        $stats = [
            'todo'        => $employee->tasks()->where('status', 'todo')->count(),
            'in_progress' => $employee->tasks()->where('status', 'in_progress')->count(),
            'review'      => $employee->tasks()->where('status', 'review')->count(),
            'done'        => $employee->tasks()->where('status', 'done')->count(),
        ];

        return view('employee.tasks.index', compact('tasks', 'stats'));
    }

    public function show(Task $task)
    {
        $employee = $this->authorizeTask($task);

        $task->load(
            'client', 'service', 'employees.user',
            'comments.user', 'comments.admin',
            'statusLogs'
        );

        // الموظفون الآخرون لـ assign
       $otherEmployees = Employee::where('status', 'active')
        ->whereNotIn('id', $task->employees->pluck('id')->toArray()) // ← toArray() مهم
        ->get();

        return view('employee.tasks.show', compact('task', 'employee', 'otherEmployees'));
    }

    // ── تغيير الحالة مع تسجيل ─────────────────────────
    public function changeStatus(Request $request, Task $task)
    {
        $employee = $this->authorizeTask($task);

        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done,cancelled',
            'note'   => 'nullable|string|max:500',
        ]);

        $task->changeStatus(
            $request->status,
            'user',
            Auth::id(),
            Auth::user()->name,
            $request->note
        );

        return back()->with('success', __('employee.status_updated'));
    }

    // ── Assign موظف آخر ───────────────────────────────
    public function assignEmployee(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $request->validate(['employee_id' => 'required|exists:employees,id']);

        if (!$task->employees()->where('employee_id', $request->employee_id)->exists()) {
            $task->employees()->attach($request->employee_id, ['assigned_at' => now()]);

            // سجل في الـ history
            \App\Models\TaskStatusLog::create([
                'task_id'      => $task->id,
                'from_status'  => $task->status,
                'to_status'    => $task->status,
                'changed_by'   => Auth::id(),
                'changer_type' => 'user',
                'changer_name' => Auth::user()->name,
                'note'         => 'تم تعيين موظف جديد: ' . Employee::find($request->employee_id)?->name,
            ]);
        }

        return back()->with('success', __('employee.employee_assigned'));
    }

    // ── تعليق الموظف ──────────────────────────────────
    public function storeComment(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $request->validate(['body' => 'required|string|max:2000']);

        $task->comments()->create([
            'user_id'  => Auth::id(),
            'admin_id' => null,
            'body'     => $request->body,
        ]);

        return back()->with('success', __('employee.comment_added'));
    }

    // deprecated — kept for route compatibility
    public function markComplete(Task $task)
    {
        $employee = $this->authorizeTask($task);
        $task->changeStatus('review', 'user', Auth::id(), Auth::user()->name, 'أرسل للمراجعة');
        return back()->with('success', __('employee.task_sent_review'));
    }

    public function updateProgress(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $request->validate(['progress' => 'required|integer|min:0|max:100']);
        $task->update(['progress' => $request->progress]);
        return back()->with('success', __('employee.progress_updated'));
    }

    public function removeEmployee(Task $task, Employee $employee)
    {
        $this->authorizeTask($task);

        $task->employees()->detach($employee->id);
        return back()->with('success', __('emp.employee_removed'));
    }
}
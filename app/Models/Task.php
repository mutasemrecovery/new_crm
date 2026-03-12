<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'progress' => 'integer',
    ];

    // ========== Relations ==========

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'task_employees')
                    ->withPivot('assigned_at');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    public function statusLogs()
    {
        return $this->hasMany(TaskStatusLog::class)->latest();
    }

    // ========== Helpers ==========

    /**
     * تغيير الحالة مع تسجيل في الـ history
     */
    public function changeStatus(
        string $newStatus,
        string $changerType, // 'user' | 'admin'
        int    $changerId,
        string $changerName,
        ?string $note = null
    ): void {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) return;

        $this->update(['status' => $newStatus]);

        TaskStatusLog::create([
            'task_id'       => $this->id,
            'from_status'   => $oldStatus,
            'to_status'     => $newStatus,
            'changed_by'    => $changerId,
            'changer_type'  => $changerType,
            'changer_name'  => $changerName,
            'note'          => $note,
        ]);
    }

    // ========== Scopes ==========

    public function scopeTodo($q)       { return $q->where('status', 'todo'); }
    public function scopeInProgress($q) { return $q->where('status', 'in_progress'); }
    public function scopeInReview($q)   { return $q->where('status', 'review'); }
    public function scopeDone($q)       { return $q->where('status', 'done'); }
    public function scopeOpen($q)       { return $q->whereNotIn('status', ['done', 'cancelled']); }
    public function scopeForEmployee($q, int $employeeId)
    {
        return $q->whereHas('employees', fn($eq) => $eq->where('employee_id', $employeeId));
    }

    // ========== Accessors ==========

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['done', 'cancelled']);
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== 'done';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'todo'        => 'لم يبدأ',
            'in_progress' => 'جاري',
            'review'      => 'مراجعة',
            'done'        => 'مكتمل',
            'cancelled'   => 'ملغي',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'todo'        => '#64748b',
            'in_progress' => '#2563eb',
            'review'      => '#f59e0b',
            'done'        => '#10b981',
            'cancelled'   => '#ef4444',
            default       => '#64748b',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'عاجل',
            'high'   => 'عالية',
            'medium' => 'متوسطة',
            'low'    => 'منخفضة',
            default  => $this->priority,
        };
    }
}
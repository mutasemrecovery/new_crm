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
        // created_by -> users table (الموظف أو الأدمن اللي أنشأ)
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'task_employees')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    // ========== Scopes (للـ Kanban) ==========

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
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'done';
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

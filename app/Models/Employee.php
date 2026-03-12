<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'name_en',
        'phone',
        'email',
        'job_title',
        'department',
        'specializations',
        'salary',
        'is_sales',
        'commission_rate',
        'commission_type',
        'avatar',
        'status',
        'hire_date',
        'notes',
    ];

    protected $casts = [
        'specializations' => 'array',
        'is_sales'        => 'boolean',
        'hire_date'       => 'date',
        'salary'          => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    // ========== Relations ==========

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_employees')
            ->withPivot('assigned_at');
    }

    public function activeTasks()
    {
        return $this->tasks()->whereNotIn('status', ['done', 'cancelled']);
    }

    public function commissions()
    {
        return $this->hasMany(SalesCommission::class);
    }

    public function pendingCommissions()
    {
        return $this->commissions()->where('status', 'pending');
    }

    // ========== Scopes ==========

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
    public function scopeIsSales($q)
    {
        return $q->where('is_sales', true);
    }

    // ========== Accessors ==========

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : '');
    }

    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department) {
            'design'       => 'تصميم',
            'video'        => 'فيديو',
            'development'  => 'برمجة',
            'social_media' => 'سوشيال ميديا',
            'marketing'    => 'تسويق',
            'sales'        => 'مبيعات',
            'accounting'   => 'محاسبة',
            'management'   => 'إدارة',
            default        => $this->department,
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('assets/admin/uploads/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a3358&color=fff';
    }

    public function getPendingCommissionsAmountAttribute(): float
    {
        return (float) $this->pendingCommissions()->sum('amount');
    }
}

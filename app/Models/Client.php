<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'contract_start' => 'date',
        'contract_end'   => 'date',
        'monthly_value'  => 'decimal:2',
    ];

    // ─── Relations ───────────────────────────────────────────────

    /**
     * الخدمات عبر pivot (belongsToMany)
     * الاستخدام: $client->services → collection of Service models
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'client_services')
            ->withPivot('price', 'details', 'status', 'start_date', 'monthly_quantity', 'distribute_weekly')
            ->withTimestamps();
    }

    /**
     * صفوف الـ pivot مباشرةً كـ ClientService models
     * الاستخدام: $client->clientServices → collection of ClientService models
     * هذا هو الـ relationship اللي كان مفقود وسبّب الـ error!
     */
    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    public function assignedSales()
    {
        return $this->belongsTo(User::class, 'assigned_sales_id');
    }

    public function commissions()
    {
        return $this->hasMany(SalesCommission::class);
    }

    // ─── Workload Helper ─────────────────────────────────────────

    /**
     * احسب إجمالي الدقائق الشهرية المطلوبة لجميع خدمات هذا العميل
     *
     * مثال: تصميم صور (12 صورة × 60 دقيقة) + إدارة إعلانات (4 × 90 دقيقة) = 1080 دقيقة
     */
    public function getTotalMonthlyMinutesAttribute(): int
    {
        return $this->clientServices()
            ->with('service')
            ->get()
            ->sum(function ($cs) {
                return $cs->monthly_quantity * ($cs->service->estimated_minutes_per_unit ?? 0);
            });
    }

    /**
     * الكمية الأسبوعية لخدمة معينة (للخدمات المتكررة)
     * مثال: 12 صورة / شهر → 3 أسبوعياً
     */
    public function weeklyQuantityForService(int $serviceId): float
    {
        $cs = $this->clientServices()->where('service_id', $serviceId)->first();
        if (!$cs || !$cs->distribute_weekly) return 0;
        return round($cs->monthly_quantity / 4, 1);
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeActive($q)       { return $q->where('status', 'active'); }
    public function scopeHighPriority($q) { return $q->where('priority', 'high'); }
    public function scopeOldestFirst($q)  { return $q->orderBy('created_at', 'asc'); }

    // ─── Accessors ───────────────────────────────────────────────

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'high'   => '#ef4444',
            'medium' => '#f59e0b',
            'low'    => '#10b981',
            default  => '#64748b',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'  => 'badge-active',
            'pending' => 'badge-pending',
            'paused'  => 'badge-paused',
            'closed'  => 'badge-closed',
            default   => 'badge-muted',
        };
    }

    public function getOpenTasksCountAttribute(): int
    {
        return $this->tasks()->whereNotIn('status', ['done', 'cancelled'])->count();
    }
}
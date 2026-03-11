<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'distribute_weekly' => 'boolean',
        'start_date'        => 'date',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    /**
     * إجمالي الدقائق الشهرية لهذه الخدمة عند هذا العميل
     * مثال: 12 صورة × 60 دقيقة = 720 دقيقة/شهر
     */
    public function getTotalMonthlyMinutesAttribute(): int
    {
        return $this->monthly_quantity * ($this->service->estimated_minutes_per_unit ?? 0);
    }

    /**
     * الكمية الأسبوعية (فقط للخدمات المتكررة)
     * مثال: 12 / 4 = 3 صور أسبوعياً
     */
    public function getWeeklyQuantityAttribute(): float
    {
        if (!$this->distribute_weekly || $this->service?->isProject()) {
            return 0;
        }
        return round($this->monthly_quantity / 4, 1);
    }

    /**
     * الدقائق الأسبوعية المطلوبة
     */
    public function getWeeklyMinutesAttribute(): int
    {
        return (int) ($this->weekly_quantity * ($this->service->estimated_minutes_per_unit ?? 0));
    }

    /**
     * هل هذه خدمة مشروع؟ (الكمية ثابتة = مشروع كامل)
     */
    public function isProjectService(): bool
    {
        return $this->service?->isProject() ?? false;
    }
}
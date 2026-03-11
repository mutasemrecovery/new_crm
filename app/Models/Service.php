<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_services')
            ->withPivot('price', 'details', 'status', 'start_date', 'monthly_quantity', 'distribute_weekly')
            ->withTimestamps();
    }

    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    /**
     * هل الخدمة متكررة؟ (تصميم صور، محتوى...)
     */
    public function isRecurring(): bool
    {
        return $this->service_type === 'recurring';
    }

    /**
     * هل الخدمة مشروع؟ (موقع، تطبيق...)
     */
    public function isProject(): bool
    {
        return $this->service_type === 'project';
    }

    /**
     * احسب الساعات الكاملة من الدقائق
     */
    public function getEstimatedHoursAttribute(): float
    {
        return round($this->estimated_minutes_per_unit / 60, 1);
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeRecurring($q)
    {
        return $q->where('service_type', 'recurring');
    }

    public function scopeProject($q)
    {
        return $q->where('service_type', 'project');
    }
}
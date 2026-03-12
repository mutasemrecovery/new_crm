<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
 
class Attendance extends Model
{
    protected $guarded = [];
 
    protected $casts = [
        'date'      => 'date',
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
    ];
 
    // ── Relations ─────────────────────────────────────
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
 
    // ── Helpers ───────────────────────────────────────
    public function getWorkedMinutesAttribute(): ?int
    {
        if ($this->check_in && $this->check_out) {
            return (int) $this->check_in->diffInMinutes($this->check_out);
        }
        return null;
    }
 
    public function getWorkedHoursFormattedAttribute(): string
    {
        $mins = $this->worked_minutes;
        if ($mins === null) return '—';
        $h = intdiv($mins, 60);
        $m = $mins % 60;
        return "{$h}h {$m}m";
    }
 
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'present'  => '#43e97b',
            'late'     => '#f7b731',
            'half_day' => '#00c6ff',
            'absent'   => '#ff6584',
            default    => '#6b7280',
        };
    }
 
    public function getIsCheckedOutAttribute(): bool
    {
        return !is_null($this->check_out);
    }
 
    // ── Haversine distance in meters ──────────────────
    public static function distanceMeters(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): float {
        $earthR = 6371000; // متر
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) ** 2 +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) ** 2;
        return $earthR * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

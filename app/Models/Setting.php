<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];
 
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }
 
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting_{$key}");
    }
 
    // ── Company location helpers ───────────────────────
    public static function companyLat(): ?float
    {
        $v = static::get('company_lat');
        return $v !== null ? (float) $v : null;
    }
 
    public static function companyLng(): ?float
    {
        $v = static::get('company_lng');
        return $v !== null ? (float) $v : null;
    }
 
    public static function attendanceRadius(): float
    {
        return (float) static::get('attendance_radius', 200);
    }
 
    public static function hasLocation(): bool
    {
        return static::companyLat() !== null && static::companyLng() !== null;
    }
}

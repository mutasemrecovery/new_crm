<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
 
class WorkSchedule extends Model
{
    protected $guarded = [];
 
    protected $casts = [
        'is_working_day' => 'boolean',
    ];
 
    // أسماء الأيام
    public static array $dayNames = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];
 
    public static array $dayNamesAr = [
        0 => 'الأحد',
        1 => 'الاثنين',
        2 => 'الثلاثاء',
        3 => 'الأربعاء',
        4 => 'الخميس',
        5 => 'الجمعة',
        6 => 'السبت',
    ];
 
    // ── الحصول على جدول اليوم المحدد ──────────────────
    public static function forDay(Carbon $date): ?self
    {
        $dow = (int) $date->dayOfWeek; // 0=Sun … 6=Sat
        return Cache::remember("work_schedule_{$dow}", 3600, function () use ($dow) {
            return static::where('day_of_week', $dow)->first();
        });
    }
 
    // ── الحصول على كل الأيام مرتبة ───────────────────
    public static function allOrdered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderBy('day_of_week')->get();
    }
 
    // ── حساب ساعات العمل المتوقعة في هذا اليوم ───────
    public function expectedMinutes(): int
    {
        if (!$this->is_working_day || !$this->start_time || !$this->end_time) return 0;
        $start = Carbon::parse($this->start_time);
        $end   = Carbon::parse($this->end_time);
        return (int) $start->diffInMinutes($end);
    }
 
    // ── حساب التأخير بالدقائق ─────────────────────────
    public function lateMinutes(Carbon $checkIn): int
    {
        if (!$this->is_working_day || !$this->start_time) return 0;
        $deadline = Carbon::parse($checkIn->format('Y-m-d') . ' ' . $this->start_time)
                          ->addMinutes($this->grace_minutes);
        if ($checkIn->gt($deadline)) {
            return (int) Carbon::parse($checkIn->format('Y-m-d') . ' ' . $this->start_time)
                               ->diffInMinutes($checkIn);
        }
        return 0;
    }
 
    // ── حساب الأوفرتايم بالدقائق ─────────────────────
    public function overtimeMinutes(Carbon $checkOut): int
    {
        if (!$this->is_working_day || !$this->end_time) return 0;
        $endTime = Carbon::parse($checkOut->format('Y-m-d') . ' ' . $this->end_time);
        if ($checkOut->gt($endTime)) {
            return (int) $endTime->diffInMinutes($checkOut);
        }
        return 0;
    }
 
    // ── clear cache عند التحديث ───────────────────────
    public static function clearCache(): void
    {
        for ($i = 0; $i <= 6; $i++) {
            Cache::forget("work_schedule_{$i}");
        }
    }
 
    // ── عدد أيام العمل في شهر معين ───────────────────
    public static function workingDaysInMonth(int $year, int $month): int
    {
        $schedules = static::where('is_working_day', true)->pluck('day_of_week')->toArray();
        $start = Carbon::create($year, $month, 1);
        $end   = $start->copy()->endOfMonth();
        $count = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (in_array((int)$d->dayOfWeek, $schedules)) $count++;
        }
        return $count;
    }
}
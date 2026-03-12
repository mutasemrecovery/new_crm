<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LeaveBalance extends Model
{
    protected $guarded = [];

    public function employee() { return $this->belongsTo(Employee::class); }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->annual_balance - $this->used_days);
    }

    // الحصول أو إنشاء رصيد سنة معينة
    public static function getOrCreate(int $employeeId, int $year): self
    {
        return static::firstOrCreate(
            ['employee_id' => $employeeId, 'year' => $year],
            ['annual_balance' => 21, 'used_days' => 0]
        );
    }
}

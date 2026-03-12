<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payroll extends Model
{
    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'date',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }

    // ── Computed ──────────────────────────────────────
    public function getTotalAdditionsAttribute(): float
    {
        return $this->basic_salary + $this->commissions_amount + $this->bonuses;
    }

    public function getTotalDeductionsAttribute(): float
    {
        return $this->deduction_absence + $this->deduction_late + $this->deduction_manual;
    }

    public function getMonthNameAttribute(): string
    {
        return Carbon::create($this->year, $this->month)->translatedFormat('F Y');
    }

    public function isDraft(): bool { return $this->status === 'draft'; }
    public function isPaid(): bool  { return $this->status === 'paid'; }

    // ── Build payroll from attendance ─────────────────
   public static function build(Employee $employee, int $year, int $month): array
    {
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // أيام العمل الفعلية من جدول الدوام
        $workingDaysInMonth = WorkSchedule::workingDaysInMonth($year, $month);

        $presentDays   = $attendances->count();
        $absentDays    = max(0, $workingDaysInMonth - $presentDays);
        $lateCount     = $attendances->where('status', 'late')->count();
        $totalLateMin  = $attendances->sum('late_minutes');
        $totalOtMin    = $attendances->sum('overtime_minutes');
        $totalOtHours  = round($totalOtMin / 60, 2);

        // معدلات الخصم والأوفرتايم من الإعدادات
        $absenceDeductionType = Setting::get('absence_deduction_type', 'daily');  // daily | percentage
        $absenceDeductionVal  = (float) Setting::get('absence_deduction_value', 0);
        $lateDeductionPerMin  = (float) Setting::get('late_deduction_per_minute', 0);
        $overtimeRatePerHour  = (float) Setting::get('overtime_rate_per_hour', 0);

        // حساب خصم الغياب
        $dailyRate = $workingDaysInMonth > 0 ? $employee->salary / $workingDaysInMonth : 0;

        if ($absenceDeductionType === 'percentage') {
            // نسبة من اليومية
            $deductAbs = round($dailyRate * ($absenceDeductionVal / 100) * $absentDays, 2);
        } else {
            // قيمة ثابتة لكل يوم غياب (0 = يومية كاملة)
            $perDayDeduct = $absenceDeductionVal > 0 ? $absenceDeductionVal : $dailyRate;
            $deductAbs    = round($perDayDeduct * $absentDays, 2);
        }

        // حساب خصم التأخير
        $deductLate = $lateDeductionPerMin > 0
            ? round($lateDeductionPerMin * $totalLateMin, 2)
            : round(($dailyRate / 480) * $totalLateMin, 2); // افتراضي: يومية/8ساعات

        // حساب الأوفرتايم
        $otAmount = round($overtimeRatePerHour * $totalOtHours, 2);

        // العمولات
        $commissions = SalesCommission::where('employee_id', $employee->id)
            ->where('status', 'paid')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $netSalary = round(
            $employee->salary
            + $commissions
            + $otAmount
            - $deductAbs
            - $deductLate,
            2
        );

        return [
            'basic_salary'       => $employee->salary,
            'commissions_amount' => $commissions,
            'bonuses'            => 0,
            'overtime_hours'     => $totalOtHours,
            'overtime_amount'    => $otAmount,
            'deduction_absence'  => $deductAbs,
            'deduction_late'     => $deductLate,
            'deduction_manual'   => 0,
            'working_days'       => $presentDays,
            'absent_days'        => $absentDays,
            'late_count'         => $lateCount,
            'late_minutes_total' => $totalLateMin,
            'net_salary'         => max(0, $netSalary),
        ];
    }
}





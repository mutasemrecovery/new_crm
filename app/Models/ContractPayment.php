<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'date',
        'amount'   => 'decimal:2',
    ];

    // ─── Relations ────────────────────────────────────────
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function receipt()
    {
        return $this->hasOne(PaymentReceipt::class);
    }

    public function commissions()
    {
        return $this->hasMany(SalesCommission::class);
    }

    // ─── Helpers ──────────────────────────────────────────

    /** هل الدفعة متأخرة؟ */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date->isPast();
    }

    /** أيام التأخير */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;
        return now()->diffInDays($this->due_date);
    }

    /**
     * سجّل الدفعة كمدفوعة + أنشئ سند قبض تلقائياً
     */
    public function markAsPaid(array $paymentData = []): PaymentReceipt
    {
        $this->update([
            'status'         => 'paid',
            'paid_at'        => $paymentData['paid_at'] ?? now()->toDateString(),
            'payment_method' => $paymentData['payment_method'] ?? null,
            'reference'      => $paymentData['reference'] ?? null,
            'notes'          => $paymentData['notes'] ?? $this->notes,
        ]);

        // أنشئ سند القبض
        $receipt = $this->receipt()->create([
            'receipt_number' => PaymentReceipt::generateNumber(),
            'issued_by'      => $paymentData['issued_by'] ?? auth()->id(),
            'amount'         => $this->amount,
            'receipt_date'   => $paymentData['paid_at'] ?? now()->toDateString(),
            'payment_method' => $paymentData['payment_method'] ?? null,
            'reference'      => $paymentData['reference'] ?? null,
            'notes'          => $paymentData['notes'] ?? null,
        ]);

        // تحقق إذا اكتمل العقد
        $contract = $this->contract;
        $allPaid  = $contract->payments()->where('status', '!=', 'paid')->doesntExist();
        if ($allPaid) {
            $contract->update(['status' => 'completed']);
        }

        // أنشئ عمولة تلقائياً إذا كان العميل عنده مندوب مبيعات
        $this->createCommissionIfNeeded();

        return $receipt;
    }

    private function createCommissionIfNeeded(): void
    {
        $client = $this->contract->client;
        if (!$client->assigned_sales_id) return;

        // نسبة عمولة افتراضية 10% — تقدر تجعلها configurable لاحقاً
        $rate = 10;
        SalesCommission::create([
            'employee_id'         => $client->assigned_sales_id,
            'client_id'           => $client->id,
            'contract_id'         => $this->contract_id,
            'contract_payment_id' => $this->id,
            'amount'              => round($this->amount * $rate / 100, 2),
            'rate'                => $rate,
            'status'              => 'pending',
        ]);
    }

    // ─── Scopes ───────────────────────────────────────────
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopePaid($q)     { return $q->where('status', 'paid'); }
    public function scopeOverdue($q)  { return $q->where('status', 'pending')->where('due_date', '<', now()); }
}

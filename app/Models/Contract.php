<?php
// ════════════════════════════════════════════════════════════════
// App\Models\Contract
// ════════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'total_amount' => 'decimal:2',
        'net_amount'   => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
    ];

    // ─── Auto-generate contract number ────────────────────
    protected static function booted(): void
    {
        static::creating(function ($contract) {
            $year  = now()->format('Y');
            $count = static::whereYear('created_at', $year)->count() + 1;
            $contract->contract_number = 'CTR-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }

    // ─── Relations ────────────────────────────────────────
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class);
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class)->orderBy('payment_number');
    }

    public function commissions()
    {
        return $this->hasMany(SalesCommission::class);
    }

    // ─── Helpers ──────────────────────────────────────────

    /** المبلغ المدفوع حتى الآن */
    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /** المبلغ المتبقي */
    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->net_amount - $this->paid_amount;
    }

    /** نسبة الإنجاز المالي */
    public function getPaymentProgressAttribute(): int
    {
        if ($this->net_amount <= 0) return 0;
        return (int) min(100, round(($this->paid_amount / $this->net_amount) * 100));
    }

    /** هل هناك دفعات متأخرة؟ */
    public function hasOverduePayments(): bool
    {
        return $this->payments()
            ->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->exists();
    }

    // ─── Scopes ───────────────────────────────────────────
    public function scopeActive($q)    { return $q->where('status', 'active'); }
    public function scopeCompleted($q) { return $q->where('status', 'completed'); }
}
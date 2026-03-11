<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'receipt_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function contractPayment()
    {
        return $this->belongsTo(ContractPayment::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /** اختصار للوصول للعقد */
    public function getContractAttribute(): Contract
    {
        return $this->contractPayment->contract;
    }

    public static function generateNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'RCP-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}

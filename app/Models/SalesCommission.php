<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCommission extends Model
{
   protected $guarded = [];

    protected $casts = [
        'paid_at' => 'date',
        'amount'  => 'decimal:2',
        'rate'    => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

   

    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }
    public function scopePaid($q)
    {
        return $q->where('status', 'paid');
    }
}

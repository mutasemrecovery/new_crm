<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $guarded = [];
    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function reviewer() { return $this->belongsTo(Admin::class, 'reviewed_by'); }

    // ── Helpers ───────────────────────────────────────
    public static function calcDays(string $start, string $end): int
    {
        return (int) \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'  => '#f7b731',
            'approved' => '#43e97b',
            'rejected' => '#ff6584',
            default    => '#6b7280',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'annual'    => '#6c63ff',
            'sick'      => '#00c6ff',
            'emergency' => '#ff6584',
            'unpaid'    => '#6b7280',
            'other'     => '#f7b731',
            default     => '#6b7280',
        };
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}

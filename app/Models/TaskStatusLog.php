<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatusLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // ─── Status colors/icons helper ───────────────────
    public static function statusColor(string $status): string
    {
        return match($status) {
            'todo'        => '#6b7280',
            'in_progress' => '#00c6ff',
            'review'      => '#f7b731',
            'done'        => '#43e97b',
            'cancelled'   => '#ff6584',
            default       => '#6b7280',
        };
    }

    public static function statusIcon(string $status): string
    {
        return match($status) {
            'todo'        => 'fa-circle',
            'in_progress' => 'fa-spinner',
            'review'      => 'fa-clock',
            'done'        => 'fa-check-circle',
            'cancelled'   => 'fa-times-circle',
            default       => 'fa-circle',
        };
    }
}
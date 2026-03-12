<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function likes()
    {
        return $this->hasMany(TaskCommentLike::class);
    }

    // ── Helper: الاسم مهما كان المصدر ─────────────────
    public function getAuthorNameAttribute(): string
    {
        if ($this->admin_id && $this->admin) {
            return $this->admin->name ?? 'Admin';
        }
        return $this->user?->name ?? __('admin.unknown');
    }

    public function getIsAdminAttribute(): bool
    {
        return !is_null($this->admin_id);
    }

    public function getInitialAttribute(): string
    {
        return strtoupper(mb_substr($this->author_name, 0, 1));
    }
}
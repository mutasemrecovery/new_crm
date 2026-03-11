<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCommentLike extends Model
{
    protected $fillable = [
        'task_comment_id',
        'user_id',
    ];

    public function comment()
    {
        return $this->belongsTo(TaskComment::class, 'task_comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

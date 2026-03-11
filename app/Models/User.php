<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;


   protected $guarded = [];


   protected $hidden = [
      'password',
      'remember_token',
   ];


    protected $casts = [
        'activate' => 'integer',
    ];

    // ========== Relations ==========

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(TaskCommentLike::class);
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    // ========== Helpers ==========

    public function isActive(): bool
    {
        return $this->activate === 1;
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn($w) => mb_substr($w, 0, 1))
            ->implode('');
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('assets/admin/uploads/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a3358&color=fff';
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles,HasApiTokens;


    protected $table="admins";
    protected $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'is_super',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_super' => 'boolean',
    ];

    // ========== Helpers ==========

    public function isSuperAdmin(): bool
    {
        return $this->is_super === true;
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn($w) => mb_substr($w, 0, 1))
            ->implode('');
    }

}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read \App\Models\UserAvatar|null $avatar
 * @property-read \App\Models\UserBackgroundConver|null $background
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'device_info',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'device_info' => 'array',
    ];

    public function avatar(): HasOne
    {
        return $this->hasOne(UserAvatar::class);
    }

    public function background(): HasOne
    {
        return $this->hasOne(UserBackgroundConver::class);
    }
}
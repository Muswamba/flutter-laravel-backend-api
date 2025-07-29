<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAvatar extends Model
{
    protected $fillable = ['user_id', 'avatar_path','description', 'mime_type', 'size'];

    protected $casts = [
        'user_id' => 'integer',
        'avatar_path' => 'string',
    ];

    /**
     * Relationship: Avatar belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    /**
     * Accessor: Get full URL for avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? asset('storage/' . $this->avatar_path)
            : asset('images/default-avatar.png'); // fallback image
    }

    /**
     * Mutator: Set the avatar path safely.
     */
    public function setAvatarPathAttribute($value): void
    {
        $this->attributes['avatar_path'] = $value;
    }
}
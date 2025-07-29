<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBackgroundConver extends Model
{
    protected $fillable = ['user_id', 'background_path', 'description', 'mime_type', 'size'];

    protected $casts = [
        'user_id' => 'integer',
        'background_path' => 'string',
    ];

    /**
     * Relationship: Background belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Get full URL for background image.
     */
    public function getBackgroundUrlAttribute(): string
    {
        return $this->background_path
            ? asset('storage/' . $this->background_path)
            : asset('images/default-background.png'); // fallback
    }

    /**
     * Mutator: Set the background path.
     */
    public function setBackgroundPathAttribute($value): void
    {
        $this->attributes['background_path'] = $value;
    }
}
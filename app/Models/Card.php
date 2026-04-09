<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps();
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}

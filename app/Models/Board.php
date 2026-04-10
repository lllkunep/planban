<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'last_active'])
            ->withTimestamps();
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}

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

    protected $fillable = ['column_id', 'name', 'text', 'assigned_user_id', 'position'];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function histories(): HasMany
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

    public function belongsToBoard(Board $board): bool
    {
        return $this->column->board_id === $board->id;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('position', 'asc')->orderBy('id', 'desc');
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color', 'board_id'];
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class)
            ->withTimestamps();
    }

    public function belongsToBoard(Board $board): bool
    {
        return $this->board_id === $board->id;
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('id', 'desc');
        });
    }
}

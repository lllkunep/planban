<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    protected $fillable = ['user_id', 'card_id', 'text'];
    protected $appends = ['canUpdate', 'canDelete'];

    use SoftDeletes;

    public function getCanUpdateAttribute(): bool
    {
        return auth()->check() && auth()->user()->can('update', $this);
    }

    public function getCanDeleteAttribute(): bool
    {
        return auth()->check() && auth()->user()->can('delete', $this);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function belongsToCard(Card $card): bool
    {
        return $this->card_id === $card->id;
    }
}

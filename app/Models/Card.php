<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function assignedUsers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function belongsToBoard(Board $board): bool
    {
        return $this->column->board_id === $board->id;
    }

    public function move(Column $column, int $position): void
    {
        DB::transaction(function () use ($column, $position) {
            $this->update(['position' => $position, 'column_id' => $column->id]);

            $columnCards = Card::where('column_id', $this->column_id)
                ->where('id', '!=', $this->id)
                ->orderBy('position')
                ->get();

            $j = 0;
            for ($i = 0; $i < $columnCards->count(); $i++) {
                if ($i == $position){
                    $j = 1;
                }
                $columnCards[$i]->position = $i + $j;
                $columnCards[$i]->save();
            }
        });
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('position', 'asc')->orderBy('id', 'desc');
        });
    }
}

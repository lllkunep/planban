<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Column extends Model
{
    use SoftDeletes;

    protected $fillable = ['board_id', 'name', 'position'];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function belongsToBoard(Board $board): bool
    {
        return $this->board_id === $board->id;
    }

    public function move($position): void
    {
        DB::transaction(function () use ($position) {
            $this->update(['position' => $position]);

            $boardColumns = Column::where('board_id', $this->board_id)
                ->where('id', '!=', $this->id)
                ->orderBy('position')
                ->get();

            $j = 0;
            for ($i = 0; $i < $boardColumns->count(); $i++) {
                if ($i == $position){
                    $j = 1;
                }
                $boardColumns[$i]->position = $i + $j;
                $boardColumns[$i]->save();
            }
        });
    }


    protected static function booted(): void
    {
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('position', 'desc')->orderBy('id', 'desc');
        });
    }
}

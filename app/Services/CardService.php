<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CardService
{
    public function store(Board $board, Column $column, array $data): Card
    {
        if (!$column->belongsToBoard($board)){
            throw new ModelNotFoundException('Column not found in this board');
        }

        return $column->cards()->create($data);
    }

    public function update(Board $board, Card $card, array $data): Card
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        $card->update(
            collect($data)->only(['name', 'text', 'assigned_user_id'])->toArray()
        );

        if (isset($data['tags'])) {
            $tagIds = collect($data['tags'])->pluck('id');
            $card->tags()->sync($tagIds);
        }

        return $card;
    }

    public function move(Board $board, Column $column, Card $card, int $position): Card
    {
        if (!$column->belongsToBoard($board) || !$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        DB::transaction(function () use ($card, $column, $position) {
            $card->update(['position' => $position, 'column_id' => $column->id]);

            $columnCards = Card::where('column_id', $card->column_id)
                ->where('id', '!=', $card->id)
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

        return $card;
    }

    public function delete(Board $board, Card $card): void
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }
        $card->delete();
    }
}

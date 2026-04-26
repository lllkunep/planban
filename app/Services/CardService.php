<?php

namespace App\Services;

use App\Events\CardUpdated;
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

        return DB::transaction(function () use ($column, $data) {
            $card = $column->cards()->create($data);

            CardUpdated::dispatch($card, auth()->user(), "Created");

            return $card;
        });
    }

    public function update(Board $board, Card $card, array $data): Card
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        return DB::transaction(function () use ($card, $data) {
            $oldData = $card->toArray();
            $card->update(
                collect($data)->only(['name', 'text', 'assigned_user_id'])->toArray()
            );

            $oldTags    = $card->tags;
            $oldTagsIds = $card->tags->pluck('id')->toArray();

            if (isset($data['tags'])) {
                $tagIds = collect($data['tags'])->pluck('id');
                $card->tags()->sync($tagIds);
            }

            $card->load('tags');
            $newTags = $card->tags;
            $newTagsIds = collect($data['tags'])->pluck('id')->toArray() ?? [];

            if ($card->wasChanged('name')) {
                CardUpdated::dispatch($card, auth()->user(), "Changed name from {$oldData['name']} to {$card->name}");
            }
            if ($card->wasChanged('text')) {
                CardUpdated::dispatch($card, auth()->user(), "Changed text");
            }
            if ($card->wasChanged('assigned_user_id')) {
                $card->load('assignedUser');
                if ($oldData['assigned_user_id'] !== $card->assigned_user_id) {
                    CardUpdated::dispatch($card, auth()->user(), "Assigned to {$card->assignedUser->name}");
                } elseif (!$card->assigned_user_id) {
                    CardUpdated::dispatch($card, auth()->user(), "Unassigned");
                }
            }
            $added   = array_diff($newTagsIds, $oldTagsIds);
            $removed = array_diff($oldTagsIds, $newTagsIds);

            if (!empty($added) || !empty($removed)) {
                $added   = $newTags->whereNotIn('id', $oldTagsIds);
                $removed = $oldTags->whereNotIn('id', $newTagsIds);

                if($removed->count() > 0){
                    $names = $removed->pluck('name')->toArray();
                    $names = implode(', ', $names);

                    CardUpdated::dispatch($card, auth()->user(), "Removed tags: {$names}");
                }
                if($added->count() > 0){
                    $names = $added->pluck('name')->toArray();
                    $names = implode(', ', $names);

                    CardUpdated::dispatch($card, auth()->user(), "Added tags: {$names}");
                }
            }

            return $card;
        });
    }

    public function move(Board $board, Column $column, Card $card, int $position): Card
    {
        if (!$column->belongsToBoard($board) || !$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        DB::transaction(function () use ($card, $column, $position) {
            $oldColumn = $card->column;
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

            if ($oldColumn->id != $column->id) {
                $user = auth()->user();
                CardUpdated::dispatch($card, $user, "Moved from {$oldColumn->name} to {$column->name}");
            }
        });

        return $card;
    }

    public function delete(Board $board, Card $card): void
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        DB::transaction(function () use ($card) {
            $card->delete();

            CardUpdated::dispatch($card, auth()->user(), "Deleted");
        });
    }
}

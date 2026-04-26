<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\Card;
use App\Repositories\Interfaces\CardRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CardRepository implements CardRepositoryInterface
{
    public function getBoardCard(Board $board, Card $card): Card
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Card not found in this board');
        }

        $card->load(['assignedUsers', 'tags', 'comments.user', 'histories.user']);

        return $card;
    }
}

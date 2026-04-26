<?php

namespace App\Repositories\Interfaces;

use App\Models\Board;
use App\Models\Card;

interface CardRepositoryInterface
{
    public function getBoardCard(Board $board, Card $card): Card;
}

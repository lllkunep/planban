<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Comment;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class CommentPolicy
{
    use ChecksBoardMembership;
    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        $board = $comment->card->column->board;
        return $comment->user_id === $user->id || $this->isAdmin($user, $board);
    }
}

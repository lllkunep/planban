<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class BoardPolicy
{
    use ChecksBoardMembership;

    public function member(User $user, Board $board): bool
    {
        return $this->isMember($user, $board);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function admin(User $user, Board $board): bool
    {
        return $this->isAdmin($user, $board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function owner(User $user, Board $board): bool
    {
        return $this->isOwner($user, $board);
    }
}

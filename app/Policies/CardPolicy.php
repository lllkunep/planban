<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\Board;
use App\Models\Card;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class CardPolicy
{
    use ChecksBoardMembership;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // filter in controller
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Card $card): bool
    {
        return $this->isMember($user, $card->column->board);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Board $board): bool
    {
        return $this->isMember($user, $board);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Card $card): bool
    {
        return $this->isMember($user, $card->column->board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): bool
    {
        return $this->isMember($user, $card->column->board);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Card $card): bool
    {
        return $this->isMember($user, $card->column->board);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Card $card): bool
    {
        return $this->isAdmin($user, $card->column->board);
    }

    public function move(User $user, Card $card): bool
    {
        return $this->isMember($user, $card->column->board);
    }
}

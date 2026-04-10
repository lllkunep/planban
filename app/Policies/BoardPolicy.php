<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\Board;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class BoardPolicy
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
    public function view(User $user, Board $board): bool
    {
        return $this->isMember($user, $board);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Board $board): bool
    {
        return $this->isAdmin($user, $board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Board $board): bool
    {
        return $this->isOwner($user, $board);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Board $board): bool
    {
        return $this->isOwner($user, $board);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Board $board): bool
    {
        return $this->isOwner($user, $board);
    }

    public function addMember(User $user, Board $board): bool
    {
        return $this->isAdmin($user, $board);
    }

    public function removeMember(User $user, Board $board, User $removedMember): bool
    {
        return $this->isAdmin($user, $board) ||
            ($this->isMember($user, $board) && $user->is($removedMember));
    }
}

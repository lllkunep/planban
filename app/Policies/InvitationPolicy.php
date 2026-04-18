<?php

namespace App\Policies;

use App\Models\Board;
use Illuminate\Auth\Access\Response;

use App\Models\Invitation;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class InvitationPolicy
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
    public function view(User $user, Invitation $invitation): bool
    {
        return $this->isMember($user, $invitation->board);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Board $board): bool
    {
        return $this->isAdmin($user, $board);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invitation $invitation): bool
    {
        return $this->isAdmin($user, $invitation->board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return $this->isAdmin($user, $invitation->board);
    }
}

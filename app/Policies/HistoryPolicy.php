<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\History;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class HistoryPolicy
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
    public function view(User $user, History $history): bool
    {
        return $this->isMember($user, $history->board);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, History $history): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, History $history): bool
    {
        return false;
    }
}

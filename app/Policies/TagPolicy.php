<?php

namespace App\Policies;

use App\Models\Board;
use Illuminate\Auth\Access\Response;

use App\Models\Tag;
use App\Models\User;
use App\Traits\ChecksBoardMembership;

class TagPolicy
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
    public function view(User $user, Tag $tag): bool
    {
        return $this->isMember($user, $tag->board);
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
    public function update(User $user, Tag $tag): bool
    {
        return $this->isMember($user, $tag->board);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $this->isAdmin($user, $tag->board);
    }
}

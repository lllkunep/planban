<?php
namespace App\Traits;

use App\Models\Board;
use App\Models\User;

trait ChecksBoardMembership {
    private function isOwner(User $user, Board $board): bool
    {
        return $board->members()
            ->where('user_id', $user->id)
            ->where('role', 'owner')
            ->exists();
    }

    private function isAdmin(User $user, Board $board): bool
    {
        return $board->members()
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    private function isMember(User $user, Board $board): bool
    {
        return $board->members()
            ->where('user_id', $user->id)
            ->where('role', 'member')
            ->exists();
    }
}

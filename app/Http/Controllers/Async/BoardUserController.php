<?php

namespace App\Http\Controllers\Async;

use App\Enums\BoardRole;
use App\Models\Board;
use App\Models\Invitation;
use App\Models\User;
use App\Traits\ChecksBoardMembership;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class BoardUserController extends AsyncController
{
    use ChecksBoardMembership;

    public function attach(Request $request, Board $board)
    {
        $this->authorize('admin', $board);
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::getByEmail($validated['email']);

        if ($user) {
            $board->members()->attach($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Member added successfully',
                'data' => $board->members()->wherePivot('user_id', $user->id)->first(),
                'type' => 'user',
            ]);
        } else {
            $invitation = Invitation::create([
                'board_id' => $board->id,
                'email' => $validated['email'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invitation added successfully',
                'data' => $board->invitations()->where('id', $invitation->id)->first(),
                'type' => 'invitation',
            ]);
        }
    }

    public function detach(Board $board, User $user)
    {
        $this->authorize('admin', $board);
        if (!$this->isMember($user, $board)) {
            throw ValidationException::withMessages([
                'user_id' => 'Member not found in this board',
            ]);
        }

        $board->members()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully',
            'data' => $board->members,
        ]);
    }

    public function changeRole(Request $request, Board $board, User $user)
    {
        $this->authorize('admin', $board);
        $validated = $request->validate([
            'role' => ['required', new Enum(BoardRole::class)],
        ]);

        $board->members()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role changed successfully',
            'data' => $board->members()->wherePivot('user_id', $user->id)->first(),
        ]);
    }

    public function setNewOwner(Board $board, User $user)
    {
        $this->authorize('owner', $board);
        $board->setNewOwner($user);

        return response()->json([
            'success' => true,
            'message' => 'Owner role set successfully',
            'data' => $board->members()->wherePivot('user_id', $user->id)->first(),
        ]);
    }

    public function removeInvitation(Board $board, Invitation $invitation)
    {
        $this->authorize('admin', $board);
        if (!$invitation->belongsToBoard($board)) {
            throw new ModelNotFoundException('Invitation not found in this board');
        }

        $invitation->delete();
        return response()->json(['ok' => true]);
    }
}

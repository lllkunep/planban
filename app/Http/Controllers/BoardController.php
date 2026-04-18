<?php

namespace App\Http\Controllers;

use App\Enums\BoardRole;
use App\Models\Board;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board): Response
    {
        $this->authorize('view', $board);

        $board->load('columns.cards');

        return Inertia::render('Board/Show', [
            'board' => $board,
        ]);
    }

    public function edit(Board $board): Response
    {
        $this->authorize('update', $board);

        $roles = collect(BoardRole::cases())->map(fn($r) => [
            'value' => $r->value,
            'label' => $r->label(),
        ]);

        $board->load(['members', 'invitations', 'tags']);

        return Inertia::render('Board/Edit/Edit', [
            'board' => $board,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $board->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return back()->with('success', 'Board updated successfully.');
    }

    public function addMember(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors()['email'][0],
            ]);
        }

        $member = User::getByEmail($validated['email']);
        if ($member) {
            $board->members()->attach($member->id);

            return response()->json([
                'type' => 'member',
                'data' => $board->members()->wherePivot('user_id', $member->id)->first(),
                'message' => 'Member added successfully',
            ]);
        } else {
            $invitation = Invitation::create([
                'board_id' => $board->id,
                'email' => $validated['email'],
            ]);

            return response()->json([
                'type' => 'invitation',
                'data' => $board->invitations()->where('id', $invitation->id)->first(),
                'message' => 'Invitation added successfully',
            ]);
        }
    }

    public function changeRole(Request $request, Board $board){
        $this->authorize('update', $board);

        $validated = $request->validate([
            'role' => ['required', new Enum(BoardRole::class)],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if (!$board->members()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['error' => 'Member not found in this board'], 422);
        }

        $board->members()->updateExistingPivot($validated['user_id'], [
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'Role changed successfully',
            'data' => $board->members()->wherePivot('user_id', $validated['user_id'])->first(),
        ]);
    }

    public function setNewOwner(Request $request, Board $board){
        $this->authorize('update', $board);
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if (!$board->members()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['error' => 'Member not found in this board'], 422);
        }

        try{
            DB::transaction(function () use ($board, $validated) {
                DB::table('board_user')
                    ->where('board_id', $board->id)
                    ->where('role', 'owner')
                    ->update(['role' => 'admin']);

                $board->members()->updateExistingPivot($validated['user_id'], [
                    'role' => 'owner',
                ]);
            });

            return response()->json([
                'message' => 'Owner role set successfully',
                'data' => $board->members()->wherePivot('user_id', $validated['user_id'])->first(),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to set owner role'], 500);
        }
    }

    public function removeMember(Request $request, Board $board){
        $this->authorize('update', $board);
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        if (!$board->members()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['error' => 'Member not found in this board'], 422);
        }

        $board->members()->detach($validated['user_id']);
        return response()->json(['ok' => true]);
    }

    public function removeInvitation(Request $request, Board $board){
        $this->authorize('update', $board);
        $validated = $request->validate([
            'invitation_id' => ['required', 'integer', 'exists:invitations,id'],
        ]);
        $board->invitations()->where('id', $validated['invitation_id'])->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $board->delete();

        return redirect()->route('dashboard');
    }
}

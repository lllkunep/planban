<?php

namespace App\Http\Controllers;

use App\Enums\BoardRole;
use App\Models\Board;
use App\Models\Card;
use App\Traits\ChecksBoardMembership;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    use ChecksBoardMembership;
    public function create()
    {
        return Inertia::render('Board/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $board = Board::create([
            'name' => $request->name,
        ]);

        $board->members()->attach($user->id, ['role' => 'owner']);

        return redirect()->route('boards.show', $board);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board): Response
    {
        $board->load('columns.cards.tags');

        return Inertia::render('Board/Show/Show', [
            'board' => $board,
        ]);
    }

    public function onCard(Board $board, Card $card): Response
    {
        $board->load('columns.cards');
        $card->load(['assignedUser', 'tags', 'comments.user', 'histories.user']);

        return Inertia::render('Board/Show/Show', [
            'board' => $board,
            'initialCard' => $card,
        ]);
    }

    public function edit(Board $board): Response
    {
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
        $this->authorize('owner', $board);
        $board->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return back()->with('success', 'Board updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        $this->authorize('owner', $board);
        $board->delete();

        return redirect()->route('dashboard');
    }

    public function detachMe(Board $board)
    {
        if ($this->isOwner(auth()->user(), $board)) {
            throw ValidationException::withMessages(['role' => 'Cannot detach owner']);
        }

        $board->members()->detach(auth()->id());

        return redirect()->route('dashboard');
    }
}

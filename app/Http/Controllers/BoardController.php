<?php

namespace App\Http\Controllers;

use App\Enums\BoardRole;
use App\Models\Board;
use Illuminate\Http\Request;
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

        return Inertia::render('Board/Show/Show', [
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

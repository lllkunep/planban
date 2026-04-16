<?php

namespace App\Http\Controllers;

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

        return Inertia::render('Board/Show', [
            'board' => $board,
        ]);
    }

    public function edit(Board $board): Response
    {
        $this->authorize('update', $board);

        return Inertia::render('Board/Edit', [
            'board' => $board,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        //
    }
}

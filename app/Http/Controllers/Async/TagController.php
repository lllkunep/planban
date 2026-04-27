<?php

namespace App\Http\Controllers\Async;

use App\Models\Board;
use App\Models\Tag;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TagController extends AsyncController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board)
    {
        $this->authorize('admin', $board);
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'color' => 'required|string|max:7',
        ]);

        $tag = $board->addTag($validated['name'], $validated['color']);

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => $tag,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Tag $tag)
    {
        $this->authorize('admin', $board);
        if (!$tag->belongsToBoard($board)) {
            throw new ModelNotFoundException('Tag not found in this board');
        }

        $request->validate([
            'name' => 'sometimes|string|max:20',
            'color' => 'sometimes|string|max:7',
        ]);

        $tag->update($request->only([
            'name',
            'color'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Tag $tag)
    {
        $this->authorize('admin', $board);
        if (!$tag->belongsToBoard($board)) {
            throw new ModelNotFoundException('Tag not found in this board');
        }

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag removed successfully',
            'data' => $board->tags,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Async;

use App\Models\Board;
use App\Models\Card;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends AsyncController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board, Card $card): JsonResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);

        $comment = $card->comments()->create([
            'text' => $validated['text'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Card $card, Comment $comment): JsonResponse
    {
        if(!$comment->belongsTo($card)) {
            throw new ModelNotFoundException('Card not found in this board');
        }

        $validated = $request->validate([
            'text' => ['required', 'string'],
        ]);
        $comment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Card $card, Comment $comment): JsonResponse
    {
        if(!$comment->belongsTo($card)) {
            throw new ModelNotFoundException('Card not found in this board');
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ok',
        ]);
    }
}

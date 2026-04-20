<?php

namespace App\Http\Controllers\Async;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends AsyncController
{
    public function show(Board $board, Card $card): JsonResponse
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Card not found in this board');
        }

        $card->load(['assignedUsers', 'tags', 'comments.user', 'histories.user']);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $card,
        ]);
    }

    public function store(Request $request, Board $board, Column $column): JsonResponse
    {
        if (!$column->belongsToBoard($board)){
            throw new ModelNotFoundException('Column not found in this board');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $card = $column->cards()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Card created successfully',
            'data' => $card,
        ], 201);
    }

    public function update(Request $request, Board $board, Card $card): JsonResponse
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Card not found in this board');
        }

        $validated = $request->validate([
            'name'             => 'sometimes|string|max:255',
            'text'             => 'sometimes|string|nullable',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id',
            'tags'             => 'sometimes|array',
            'tags.*.id'        => 'exists:tags,id',
        ]);

        $card->update(
            collect($validated)->only(['name', 'text', 'assigned_user_id'])->toArray()
        );

        if (isset($validated['tags'])) {
            $tagIds = collect($validated['tags'])->pluck('id');
            $card->tags()->sync($tagIds);
        }

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully',
            'data' => $card,
        ]);
    }

    public function move(Request $request, Board $board, Column $column, Card $card): JsonResponse
    {
        if (!$column->belongsToBoard($board) || !$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Column or card not found in this board');
        }

        $validated = $request->validate([
            'position' => 'required|integer|min:0',
        ]);

        $card->move($column, $validated['position']);

        return response()->json([
            'success' => true,
            'message' => 'Card moved successfully',
            'data' => $card,
        ]);
    }

    public function destroy(Board $board, Card $card): JsonResponse
    {
        if (!$card->belongsToBoard($board)){
            throw new ModelNotFoundException('Card not found in this board');
        }

        $card->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully',
        ]);
    }
}

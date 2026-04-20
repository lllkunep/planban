<?php

namespace App\Http\Controllers\Async;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends AsyncController
{
    public function store(Request $request, Board $board)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $column = $board->columns()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $column,
        ]);
    }

    public function move(Request $request, Board $board, Column $column): JsonResponse
    {
        if (!$column->belongsToBoard($board)) {
            throw new ModelNotFoundException('Column not found in this board');
        }

        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $column->move($validated['position']);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $column,
        ]);
    }

    public function update(Request $request, Board $board, Column $column)
    {
        if (!$column->belongsToBoard($board)) {
            throw new ModelNotFoundException('Column not found in this board');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $column->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $column,
        ]);
    }

    public function destroy(Board $board, Column $column)
    {
        if (!$column->belongsToBoard($board)) {
            throw new ModelNotFoundException('Column not found in this board');
        }

        $column->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $board->columns,
        ]);
    }
}

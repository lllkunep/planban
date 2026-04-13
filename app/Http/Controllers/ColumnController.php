<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnController extends Controller
{
    public function move(Request $request, Column $column): JsonResponse
    {
        $data = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $newPosition = $data['position'];
        $oldPosition = $column->position;

        if ($newPosition === $oldPosition) {
            return response()->json(['ok' => true]);
        }

        DB::transaction(function () use ($column, $newPosition, $oldPosition) {
            if ($newPosition < $oldPosition) {
                Column::where('board_id', $column->board_id)
                    ->whereBetween('position', [$newPosition, $oldPosition - 1])
                    ->increment('position');
            } else {
                Column::where('board_id', $column->board_id)
                    ->whereBetween('position', [$oldPosition + 1, $newPosition])
                    ->decrement('position');
            }

            $column->update(['position' => $newPosition]);
        });

        return response()->json(['ok' => true]);
    }
}
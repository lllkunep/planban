<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColumnController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'board_id' => 'required|integer|exists:boards,id',
            'name' => 'required|string|max:255',
            'position' => 'required|integer|min:0',
        ]);

        $column = Column::create($validated);
        return response()->json($column);
    }

    public function move(Request $request, Column $column): JsonResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($column, $validated) {
            $column->update(['position' => $validated['position']]);

            $boardColumns = Column::where('board_id', $column->board_id)
                ->where('id', '!=', $column->id)
                ->orderBy('position')
                ->get();

            $j = 0;
            for ($i = 0; $i < $boardColumns->count(); $i++) {
                if ($i == $validated['position']){
                    $j = 1;
                }
                $boardColumns[$i]->position = $i + $j;
                $boardColumns[$i]->save();
            }
        });

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, Column $column){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $column->update($validated);
        return response()->json($column);
    }

    public function destroy(Column $column){
        $column->delete();
        return response()->json(['ok' => true]);
    }
}

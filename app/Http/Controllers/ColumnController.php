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
        $validated = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
        ]);

        if($validated['position'] == $column->position) {
            return response()->json(['ok' => true]);
        }

        DB::transaction(function () use ($column, $validated) {
            $moveForward = $column->position > $validated['position'];
            if( $moveForward ){
                $checkOperand = '>=';
                $startPosition = $validated['position'] + 1;
            } else {
                $checkOperand = '<=';
                $startPosition = 0;
            }

            $column->update(['position' => $validated['position']]);

            $boardColumns = Column::where('board_id', $column->board_id)
                ->where('position', $checkOperand, $validated['position'])
                ->where('id', '!=', $column->id)
                ->get();

            for ($i = 0; $i < $boardColumns->count(); $i++) {
                $boardColumns[$i]->position = $startPosition + $i;
                $boardColumns[$i]->save();
            }
        });

        return response()->json(['ok' => true]);
    }
}

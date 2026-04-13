<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function move(Request $request, Card $card): JsonResponse
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'position' => 'required|integer|min:0',
            'column_id' => 'required|integer|exists:columns,id',
        ]);

        DB::transaction(function () use ($card, $validated) {
            $card->update(['position' => $validated['position'], 'column_id' => $validated['column_id']]);

            Card::where('column_id', $validated['column_id'])
                ->where('position', '>=', $validated['position'])
                ->where('id', '!=', $card->id)
                ->increment('position');
        });

        return response()->json(['ok' => true]);
    }
}

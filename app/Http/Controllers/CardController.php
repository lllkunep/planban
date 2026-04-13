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
        $data = $request->validate([
            'column_id'     => ['required', 'integer', 'exists:columns,id'],
            'after_card_id' => ['nullable', 'integer', 'exists:cards,id'],
        ]);

        $newColumnId = $data['column_id'];
        $afterCardId = $data['after_card_id'] ?? null;

        DB::transaction(function () use ($card, $newColumnId, $afterCardId) {
            Card::where('after_card_id', $card->id)
                ->update(['after_card_id' => $card->after_card_id]);

            Card::where('column_id', $newColumnId)
                ->where('after_card_id', $afterCardId)
                ->where('id', '!=', $card->id)
                ->update(['after_card_id' => $card->id]);

            $card->update([
                'column_id'     => $newColumnId,
                'after_card_id' => $afterCardId,
            ]);
        });

        return response()->json(['ok' => true]);
    }
}

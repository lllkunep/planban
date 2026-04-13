<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'column_id' => ['required', 'integer', 'exists:columns,id'],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        $position = Card::where('column_id', $data['column_id'])->max('position') + 1;

        $card = Card::create([
            'column_id' => $data['column_id'],
            'name'      => $data['name'],
            'position'  => $position,
        ]);

        return response()->json($card, 201);
    }

    public function move(Request $request, Card $card): JsonResponse
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'position' => 'required|integer|min:0',
            'column_id' => 'required|integer|exists:columns,id',
        ]);

        DB::transaction(function () use ($card, $validated) {
            if ($card->column_id != $validated['column_id']){
                $columnCards = Card::where('column_id', $card->column_id)
                    ->where('id', '!=', $card->id)
                    ->get();
                for ($i = 0; $i < $columnCards->count(); $i++) {
                    $columnCards[$i]->position = $i;
                    $columnCards[$i]->save();
                }
            }

            $moveForward = $card->position > $validated['position'] || $card->column_id != $validated['column_id'];
            if( $moveForward ){
                $checkOperand = '>=';
                $startPosition = $validated['position'] + 1;
            } else {
                $checkOperand = '<=';
                $startPosition = 0;
            }

            $card->update(['position' => $validated['position'], 'column_id' => $validated['column_id']]);

            $columnCards = Card::where('column_id', $validated['column_id'])
                ->where('position', $checkOperand, $validated['position'])
                ->where('id', '!=', $card->id)
                ->get();
            for ($i = 0; $i < $columnCards->count(); $i++) {
                $columnCards[$i]->position = $startPosition + $i;
                $columnCards[$i]->save();
            }
        });

        return response()->json(['ok' => true]);
    }
}

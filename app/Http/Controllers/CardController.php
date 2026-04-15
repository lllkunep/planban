<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function show(Card $card): JsonResponse
    {
        $card->load(['assignedUser', 'tags', 'comments.user', 'histories.user']);

        return response()->json($card);
    }

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

    public function update(Request $request, Card $card): RedirectResponse
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'name'             => 'sometimes|string|max:255',
            'text'             => 'sometimes|string|nullable',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id',
            'tags'             => 'sometimes|array',
            'tags.*'           => 'exists:tags,id',
        ]);

        $card->update([
            'name'             => $validated['name'] ?? $card->name,
            'text'             => $validated['text'] ?? $card->text,
            'assigned_user_id' => $validated['assigned_user_id'] ?? $card->assigned_user_id,
        ]);

        if (isset($validated['tags'])) {
            $card->tags()->sync($validated['tags']);
        }

        return back();
    }

    public function move(Request $request, Card $card): JsonResponse
    {
        $this->authorize('update', $card);

        $validated = $request->validate([
            'position' => 'required|integer|min:0',
            'column_id' => 'required|integer|exists:columns,id',
        ]);

        DB::transaction(function () use ($card, $validated) {
            $card->update(['position' => $validated['position'], 'column_id' => $validated['column_id']]);

            $columnCards = Card::where('column_id', $card->column_id)
                ->where('id', '!=', $card->id)
                ->orderBy('position')
                ->get();

            $j = 0;
            for ($i = 0; $i < $columnCards->count(); $i++) {
                if ($i == $validated['position']){
                    $j = 1;
                }
                $columnCards[$i]->position = $i + $j;
                $columnCards[$i]->save();
            }
        });

        return response()->json(['ok' => true]);
    }
}

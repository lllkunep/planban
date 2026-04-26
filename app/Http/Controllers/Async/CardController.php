<?php

namespace App\Http\Controllers\Async;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Repositories\Interfaces\CardRepositoryInterface;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends AsyncController
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
        private CardService $cardService,
    ){}

    public function show(Board $board, Card $card): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Ok',
            'data' => $this->cardRepository->getBoardCard($board, $card),
        ]);
    }

    public function store(Request $request, Board $board, Column $column): JsonResponse
    {
        $card = $this->cardService->store($board, $column, $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Card created successfully',
            'data' => $card,
        ], 201);
    }

    public function update(Request $request, Board $board, Card $card): JsonResponse
    {
        $card = $this->cardService->update($board, $card, $request->validate([
            'name'             => 'required|string|max:255',
            'text'             => 'sometimes|string|nullable',
            'assigned_user_id' => 'sometimes|nullable|exists:users,id',
            'tags'             => 'sometimes|array',
            'tags.*.id'        => 'exists:tags,id',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully',
            'data' => $card,
        ]);
    }

    public function move(Request $request, Board $board, Column $column, Card $card): JsonResponse
    {
        $validated = $request->validate([
            'position' => 'required|integer|min:0',
        ]);

        $card = $this->cardService->move($board, $column, $card, $validated['position']);

        return response()->json([
            'success' => true,
            'message' => 'Card moved successfully',
            'data' => $card,
        ]);
    }

    public function destroy(Board $board, Card $card): JsonResponse
    {
        $this->cardService->delete($board, $card);

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully',
        ]);
    }
}

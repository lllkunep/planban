<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'color' => 'required|string|max:7',
            'board_id' => 'required|exists:boards,id',
        ]);

        $tag = Tag::create($validated);

        return response()->json($tag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'sometimes|string|max:20',
            'color' => 'sometimes|string|max:7',
        ]);

        $tag->update($request->only([
            'name',
            'color'
        ]));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(null, 204);
    }
}

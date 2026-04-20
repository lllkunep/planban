<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Board;
use Inertia\Middleware;

class HasAccessToBoard extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $board = $request->route('board');

        if (!$board instanceof Board) {
            return $next($request);
        }

        if (!$board->hasUser($request->user())) {
            throw new ModelNotFoundException('Board not found');
        }

        return $next($request);
    }
}

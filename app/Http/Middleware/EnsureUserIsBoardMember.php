<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\User;
use Inertia\Middleware;

class EnsureUserIsBoardMember extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $board = $request->route('board');
        $user  = $request->route('user');

        if (!$board instanceof Board || !$user instanceof User) {
            return $next($request);
        }

        if (!$board->hasUser($user)) {
            throw new ModelNotFoundException();
        }

        return $next($request);
    }
}

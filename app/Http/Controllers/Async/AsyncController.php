<?php

namespace App\Http\Controllers\Async;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AsyncResponseMiddleware;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

abstract class AsyncController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;
    public static function middleware(): array
    {
        return [
            new Middleware(AsyncResponseMiddleware::class),
        ];
    }
}

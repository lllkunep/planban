<?php

namespace App\Http\Controllers\Async;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AsyncResponseMiddleware;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

abstract class AsyncController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(AsyncResponseMiddleware::class),
        ];
    }
}

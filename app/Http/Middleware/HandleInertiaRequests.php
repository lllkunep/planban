<?php

namespace App\Http\Middleware;

use App\Models\Board;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],

            'boards' => function () use ($request) {
                if (!$request->user()) return [];

                return $request->user()->assignedBoards()->orderBy('last_active', 'desc')->get();
            },

            'currentBoard' => function () use ($request) {
                if (!$request->user()) return null;

                $board = $request->route('board');

                if (!$board instanceof Board) return null;

                $board->loadMissing(['members', 'tags']);

                return $board;
            },

            'can' => function () use ($request) {
                if (!$request->user()) return [
                    'member' => false,
                    'admin' => false,
                    'owner' => false,
                ];

                $board = $request->route('board');

                if (!$board instanceof Board) return [
                    'member' => false,
                    'admin' => false,
                    'owner' => false,
                ];

                return [
                    'member' => auth()->user()->can('member', $board),
                    'admin' => auth()->user()->can('admin', $board),
                    'owner' => auth()->user()->can('owner', $board),
                ];
            },

            'hasNotification' => function () use ($request) {
                if (!$request->user()) return null;

                return $request->user()->unreadNotifications->isNotEmpty();
            },

            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ];
    }
}

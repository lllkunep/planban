<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->assignedBoards()->get();

        return Inertia::render('Dashboard', [
            'boards' => $boards,
        ]);
    }
}

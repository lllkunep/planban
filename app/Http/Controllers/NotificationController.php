<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Profile/Notifications', [
            'notifications' => auth()->user()->notifications,
        ]);
    }
}

<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cards/{card}', [CardController::class, 'show'])
        ->name('cards.show');

    Route::post('/cards', [CardController::class, 'store'])
        ->name('cards.store');

    Route::patch('/cards/{card}/edit', [CardController::class, 'update'])
        ->name('cards.update');

    Route::patch('/cards/{card}/move', [CardController::class, 'move'])
        ->name('cards.move');

});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/boards/{board}', [BoardController::class, 'show'])
        ->name('boards.show');

    Route::post('/boards', [BoardController::class, 'store'])
        ->name('boards.store');

    Route::get('/boards/{board}/edit', [BoardController::class, 'edit'])
        ->name('boards.edit');

    Route::patch('/boards/{board}', [BoardController::class, 'update'])
        ->name('boards.update');

    Route::delete('/boards/{board}', [BoardController::class, 'destroy'])
        ->name('boards.destroy');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/columns', [ColumnController::class, 'store'])
        ->name('columns.store');

    Route::patch('/columns/{column}/move', [ColumnController::class, 'move'])
        ->name('columns.move');

    Route::patch('/columns/{column}', [ColumnController::class, 'update'])
        ->name('columns.update');

    Route::delete('/columns/{column}', [ColumnController::class, 'destroy'])
        ->name('columns.destroy');
});

require __DIR__.'/auth.php';

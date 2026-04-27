<?php

use App\Http\Controllers\Async\BoardUserController;
use App\Http\Controllers\Async\CardController;
use App\Http\Controllers\Async\ColumnController;
use App\Http\Controllers\Async\CommentController;
use App\Http\Controllers\Async\TagController;
use App\Http\Controllers\BoardController;
use App\Http\Middleware\EnsureUserIsBoardMember;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('boards')->name('boards.')->middleware('can:member,board')->group(function () {
        Route::controller(BoardController::class)->group(function () {
            Route::get('/create', 'create')->name('create')->withoutMiddleware('can:member,board');
            Route::post('/', 'store')->name('store')->withoutMiddleware('can:member,board');
            Route::get('/{board}', 'show')->name('show');
            Route::get('/{board}/on/{card}', 'onCard')->name('onCard');
            Route::get('/{board}/edit', 'edit')->name('edit');
            Route::patch('/{board}', 'update')->name('update');
            Route::delete('/{board}', 'destroy')->name('destroy');
            Route::delete('/{board}/detach-me', 'detachMe')->name('detachMe');
        });

        Route::prefix('{board}/tags')->controller(TagController::class)->group(function () {
            Route::post('/', 'store')->name('tags.store');
            Route::patch('/{tag}/edit', 'update')->name('tags.update');
            Route::delete('/{tag}', 'destroy')->name('tags.destroy');
        });

        Route::prefix('{board}/users')->controller(BoardUserController::class)->middleware(EnsureUserIsBoardMember::class)->group(function () {
            Route::post('/', 'attach')->name('attach');
            Route::delete('/{user}', 'detach')->name('detach');
            Route::patch('/{user}/change-role', 'changeRole')->name('changeRole');
            Route::patch('/{user}/set-new-owner', 'setNewOwner')->name('setNewOwner');
            Route::delete('/invitations/{invitation}', 'removeInvitation')->name('removeInvitation');
        });

        Route::prefix('{board}/columns')->controller(ColumnController::class)->group(function () {
            Route::post('/', 'store')->name('columns.store');
            Route::patch('/{column}', 'update')->name('columns.update');
            Route::patch('/{column}/move', 'move')->name('columns.move');
            Route::delete('/{column}', 'destroy')->name('columns.destroy');
        });

        Route::prefix('{board}/columns/{column}/cards')->controller(CardController::class)->group(function () {
            Route::post('/', [CardController::class, 'store'])->name('cards.store');
            Route::patch('/{card}', [CardController::class, 'move'])->name('cards.move');
        });

        Route::prefix('{board}/cards')->controller(CardController::class)->group(function () {
            Route::get('/{card}', 'show')->name('cards.show');
            Route::patch('/{card}', 'update')->name('cards.update');
            Route::delete('/{card}', 'destroy')->name('cards.destroy');
        });

        Route::prefix('{board}/cards/{card}/comments')->controller(CommentController::class)->name('cards.comments.')->group(function () {
            Route::post('/', 'store')->name('store');
            Route::patch('/{comment}', 'update')->name('update');
            Route::delete('/{comment}', 'destroy')->name('destroy');
        });
    });
});

<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\UserController;

Route::group([
    'as' => 'api.',
], static function () {
    require __DIR__.'/auth.php';

    Route::group([
        'prefix' => 'dictionaries',
    ], static function () {
        Route::get('', [DictionaryController::class, 'index']);
    });

    Route::group([
        'middleware' => ['auth:sanctum'],
    ], static function () {
        Route::get('me', [UserController::class, 'me'])->name('users.me');

        Route::apiResource('animals', AnimalController::class);

        Route::group([
            'as' => 'chats.',
            'prefix' => 'chats',
        ], static function () {
            Route::get('', [ChatController::class, 'index'])->name('index');
            Route::get('{chat}', [ChatController::class, 'show'])->name('show');
        });
    });
});

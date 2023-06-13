<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;

Route::group([
    'as' => 'api.',
], static function () {
    require __DIR__.'/auth.php';

    Route::group([
        'middleware' => ['auth:sanctum'],
    ], static function () {
        /* Animals */

        Route::apiResource('animals', AnimalController::class);
        Route::post('animals/{animal}/avatar', [AnimalController::class, 'avatar'])->name('animals.avatar');

        /* Events */

        Route::apiResource('events', EventController::class);

        /* Profile */

        Route::group([
            'as' => 'profile.',
            'prefix' => 'profile',
        ], static function () {
            Route::get('', [ProfileController::class, 'show'])->name('show');
            Route::patch('', [ProfileController::class, 'update'])->name('update');
            Route::post('avatar', [ProfileController::class, 'avatar'])->name('avatar');
        });

        /* Chats */

        Route::group([
            'as' => 'chats.',
            'prefix' => 'chats',
        ], static function () {
            Route::get('', [ChatController::class, 'index'])->name('index');
            Route::get('{chat}', [ChatController::class, 'show'])->name('show');
        });

        /* Dictionaries */

        Route::group([
            'as' => 'dictionaries.',
            'prefix' => 'dictionaries',
        ], static function () {
            Route::get('', [DictionaryController::class, 'index'])->name('index');
            Route::get('repeatable', [DictionaryController::class, 'repeatable'])->name('repeatable');
        });
    });
});

<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\AppealController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DictionaryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\PinController;
use App\Http\Controllers\Api\PinMediaController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ReviewMediaController;
use App\Http\Controllers\Api\UserController;

Route::group([
    'as' => 'api.',
    'prefix' => 'v1',
], static function () {
    require __DIR__ . '/auth.php';

    Route::group([
        'middleware' => ['auth:sanctum'],
    ], static function () {
        Route::any('ping', static fn () => null)->name('ping');

        /* Animals */

        Route::apiResource('animals', AnimalController::class);
        Route::post('animals/{animal}/avatar', [AnimalController::class, 'avatar'])->name('animals.avatar');

        /* Events */

        Route::apiResource('events', EventController::class);

        /* Notes */

        Route::apiResource('notes', NoteController::class);

        /* Pins */

        Route::group([
            'as' => 'pins.',
            'prefix' => 'pins',
        ], static function () {
            Route::get('search', [PinController::class, 'search'])->name('search');

            Route::group([
                'as' => 'media.',
                'prefix' => '{pin}/media'
            ], static function () {
                Route::get('/', [PinMediaController::class, 'index'])->name('index');
                Route::post('/', [PinMediaController::class, 'store'])->name('store');
                Route::delete('{media:uuid}', [PinMediaController::class, 'destroy'])->name('destroy');
            });

            Route::group([
                'as' => 'reviews.media.',
                'prefix' => '{pin}/reviews/{review}/media'
            ], static function () {
                Route::get('/', [ReviewMediaController::class, 'index'])->name('index');
                Route::post('/', [ReviewMediaController::class, 'store'])->name('store');
                Route::delete('{media:uuid}', [ReviewMediaController::class, 'destroy'])->name('destroy');
            });
        });

        Route::apiResource('pins.reviews', ReviewController::class)->except('show');
        Route::apiResource('pins', PinController::class);

        /* Users */

        Route::group([
            'as' => 'users.',
            'prefix' => 'users'
        ], static function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('coordinates', [UserController::class, 'showCoordinates'])->name('coordinates.show');
            Route::post('coordinates', [UserController::class, 'storeCoordinates'])->name('coordinates.store');
        });

        /* Profile */

        Route::group([
            'as' => 'profile.',
            'prefix' => 'profile',
        ], static function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::post('avatar', [ProfileController::class, 'avatar'])->name('avatar');
        });

        /* Chats */

        Route::group([
            'as' => 'chats.',
            'prefix' => 'chats',
        ], static function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('{chat}', [ChatController::class, 'show'])->name('show');
        });

        /* Appeals */

        Route::post('appeals', AppealController::class)->name('appeals.store');

        /* Dictionaries */

        Route::group([
            'as' => 'dictionaries.',
            'prefix' => 'dictionaries',
        ], static function () {
            Route::get('/', [DictionaryController::class, 'index'])->name('index');
            Route::get('repeatable', [DictionaryController::class, 'repeatable'])->name('repeatable');
        });
    });
});

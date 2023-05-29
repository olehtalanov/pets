<?php

use App\Http\Controllers\Api\AnimalController;
use App\Http\Controllers\Api\UserController;

Route::group([
    'as' => 'api.',
], static function () {
    require __DIR__ . '/auth.php';

    Route::group([
        'middleware' => ['auth:sanctum']
    ], static function () {
        Route::get('me', [UserController::class, 'me'])->name('users.me');
        Route::apiResource('animals', AnimalController::class);
    });
});

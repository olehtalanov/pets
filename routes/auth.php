<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], static function () {
    Route::post('code', [AuthenticateController::class, 'code'])->name('auth.code');
    Route::post('login', [AuthenticateController::class, 'login'])->name('auth.login');

    Route::get('{provider}/redirect', [SocialiteController::class, 'link'])->name('socialite.redirect');
    Route::get('{provider}/callback', [SocialiteController::class, 'store'])->name('socialite.callback');
});

<?php

Route::redirect('/', '/dashboard');

if (App::isLocal()) {
    Route::any('dd', static function () {
        //
    });
}

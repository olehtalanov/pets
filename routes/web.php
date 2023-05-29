<?php

Route::redirect('/', '/admin');

if (App::isLocal()) {
    Route::any('dd', static function () {
        dd(
            //
        );
    });
}

<?php

use Illuminate\Support\Facades\Route;

Route::get('', function () {
    //return '';
    return 'start';
});

Route::post('', function () {
    logger()->info(request()->all());
});

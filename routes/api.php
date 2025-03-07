<?php

use Illuminate\Support\Facades\Route;

Route::get('', [App\Http\Controllers\OscilloscopeController::class, 'ping']);
Route::post('', [App\Http\Controllers\OscilloscopeController::class, 'data']);
Route::post('load', [App\Http\Controllers\OscilloscopeController::class, 'load']);

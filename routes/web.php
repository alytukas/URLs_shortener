<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\URLController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/shorten', [URLController::class, 'store']);
Route::get('/{shortUrl}', [URLController::class, 'show']);

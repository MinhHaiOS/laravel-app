<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function() {
    return response()->json([
        'message' => 'health-check passed'
    ]);
});

Route::group(['prefix'=> 'book'], function() {
    Route::get('/search', [BookController::class, 'search']);
});




<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrawingController;
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [DrawingController::class, 'index']);
Route::post('/upload', [DrawingController::class, 'upload']);

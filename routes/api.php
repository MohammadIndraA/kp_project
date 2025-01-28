<?php

use App\Http\Controllers\Api\ApiMediaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/media', [ApiMediaController::class, 'index']); 

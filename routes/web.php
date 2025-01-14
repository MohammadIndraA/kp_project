<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagemenVideoController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::group(['middleware' => 'auth'], function () {
   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/managemen-video', [ManagemenVideoController::class, 'index'])->name('managemen-video.index');
    Route::post('/managemen-video/store', [ManagemenVideoController::class, 'store'])->name('managemen-video.store');
    Route::get('/managemen-video/edit', [ManagemenVideoController::class, 'edit'])->name('managemen-video.edit');
    Route::post('/managemen-video/update/{id}', [ManagemenVideoController::class, 'update'])->name('managemen-video.update');
    Route::delete('/managemen-video/delete', [ManagemenVideoController::class, 'destroy'])->name('managemen-video.delete');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});

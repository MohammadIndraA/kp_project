<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagemenVideoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TvMediaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/tvMedia', [TvMediaController::class, 'index'])->name('tvMedia');

Route::group(['middleware' => 'auth'], function () {
   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/managemen-video', [ManagemenVideoController::class, 'index'])->name('managemen-video.index');
    Route::post('/managemen-video/store', [ManagemenVideoController::class, 'store'])->name('managemen-video.store');
    Route::get('/managemen-video/edit', [ManagemenVideoController::class, 'edit'])->name('managemen-video.edit');
    Route::put('/managemen-video/update/{id}', [ManagemenVideoController::class, 'update'])->name('managemen-video.update');
    Route::delete('/managemen-video/delete', [ManagemenVideoController::class, 'destroy'])->name('managemen-video.delete');

    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/delete', [UserController::class, 'destroy'])->name('user.delete');

        // Permissoes
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');
        Route::post('/permission-store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('/permission-edit', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::put('/permission-update/{id}', [PermissionController::class, 'update'])->name('permission.update');
        Route::delete('/permission-delete', [PermissionController::class, 'destroy'])->name('permission.delete');
    
        // roles
        Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
        Route::post('/role-store', [RoleController::class, 'store'])->name('role.store');
        Route::get('/role-edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('/role-update/{id}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/role-delete', [RoleController::class, 'destroy'])->name('role.delete');
    

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

});

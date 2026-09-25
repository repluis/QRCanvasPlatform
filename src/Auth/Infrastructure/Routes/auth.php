<?php

use Illuminate\Support\Facades\Route;
use Src\Auth\Infrastructure\Controllers\AuthController;

Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store']);
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });
});

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });
});

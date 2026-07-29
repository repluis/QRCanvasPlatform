<?php

use Illuminate\Support\Facades\Route;
use Src\Pages\Infrastructure\Controllers\PageController;

Route::middleware('web')->group(function () {
    Route::get('/page', [PageController::class, 'show'])->name('pages.show');

    Route::middleware('auth')->group(function () {
        Route::get('/canvas', [PageController::class, 'editor'])->name('pages.canvas');
        Route::post('/pages', [PageController::class, 'save'])->name('pages.save');
    });
});

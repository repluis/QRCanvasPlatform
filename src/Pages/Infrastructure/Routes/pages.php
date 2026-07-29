<?php

use Illuminate\Support\Facades\Route;
use Src\Pages\Infrastructure\Controllers\PageController;

Route::middleware('web')->group(function () {
    Route::get('/page/{uuid}', [PageController::class, 'show'])->name('pages.show');

    Route::middleware('auth')->group(function () {
        Route::get('/editor', [PageController::class, 'editor'])->name('pages.editor');
        Route::post('/pages', [PageController::class, 'save'])->name('pages.save');
    });
});

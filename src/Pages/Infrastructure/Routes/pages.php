<?php

use Illuminate\Support\Facades\Route;
use Src\Pages\Infrastructure\Controllers\PageController;
use Src\Pages\Infrastructure\Controllers\TemplateController;

Route::middleware('web')->group(function () {
    Route::get('/page', [PageController::class, 'show'])->name('pages.show');

    Route::middleware('auth')->group(function () {
        Route::get('/canvas', [PageController::class, 'editor'])->name('pages.canvas');
        Route::post('/pages', [PageController::class, 'save'])->name('pages.save');
        Route::post('/pages/toggle-status', [PageController::class, 'toggleStatus'])
            ->name('pages.toggle-status');

        // Templates
        Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
        Route::post('/pages/from-template/{template}', [TemplateController::class, 'create'])
            ->name('pages.from-template');
    });
});

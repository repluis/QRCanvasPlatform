<?php

use Illuminate\Support\Facades\Route;
use Src\Pages\Infrastructure\Controllers\PageController;

Route::get('/editor', [PageController::class, 'editor'])->name('pages.editor');
Route::post('/pages', [PageController::class, 'save'])->name('pages.save');
Route::get('/p/{slug}', [PageController::class, 'show'])->name('pages.show');

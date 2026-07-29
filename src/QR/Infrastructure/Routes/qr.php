<?php

use Illuminate\Support\Facades\Route;
use Src\QR\Infrastructure\Controllers\QRController;

Route::middleware('web')->group(function () {
    Route::get('/qr/love', [QRController::class, 'love'])->name('qr.love');
    Route::post('/qr/generate', [QRController::class, 'generate'])->name('qr.generate');
});

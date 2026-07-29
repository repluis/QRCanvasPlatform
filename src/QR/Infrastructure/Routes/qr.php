<?php

use Illuminate\Support\Facades\Route;
use Src\QR\Infrastructure\Controllers\QRController;

Route::get('/qr/love', [QRController::class, 'love'])->name('qr.love');

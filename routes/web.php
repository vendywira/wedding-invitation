<?php

use App\Http\Controllers\WeddingController;
use Illuminate\Support\Facades\Route;

// Route home default
Route::get('/', function () {
    return redirect()->route('wedding.public');
});

Route::get('/p/invitation', [WeddingController::class, 'show'])->name('wedding.show');
Route::get('/r/invitation', [WeddingController::class, 'show'])->name('wedding.show');
Route::get('/invitation', [WeddingController::class, 'show'])->name('wedding.public');

// Route untuk menyimpan RSVP
Route::post('/store-message', [WeddingController::class, 'storeMessage'])->name('wedding.store-message');

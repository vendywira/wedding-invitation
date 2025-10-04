<?php

use App\Http\Controllers\WeddingController;
use Illuminate\Support\Facades\Route;

// Route untuk tamu dengan kode khusus
Route::get('/undangan/{guest_code}', [WeddingController::class, 'show'])->name('wedding.show');

// Route untuk tamu umum dengan parameter nama
Route::get('/undangan', [WeddingController::class, 'show'])->name('wedding.general');

// Route untuk menyimpan RSVP
Route::post('/store-message', [WeddingController::class, 'storeMessage'])->name('wedding.store-message');

// Route home default
Route::get('/', function () {
    return redirect()->route('wedding.general');
});

Route::get('/p/{guest}', [WeddingController::class, 'show'])->name('wedding.show');
Route::get('/r/{guest}', [WeddingController::class, 'show'])->name('wedding.show');


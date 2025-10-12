<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\WeddingController;
use Illuminate\Support\Facades\Auth;
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


Auth::routes();

Route::get('/register', function () {
    return redirect('/login');
});

Route::post('/register', function () {
    return redirect('/login');
});

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/guests', [AdminController::class, 'storeGuest'])->name('admin.guests.store');
    Route::get('/dashboard/data', [AdminController::class, 'getDashboardData'])->name('admin.dashboard.data');
    Route::get('/guests/data', [AdminController::class, 'getGuestsData'])->name('admin.guests.data');
    Route::get('/messages/data', [AdminController::class, 'getMessagesData'])->name('admin.messages.data');
    Route::put('/admin/guests/{id}', [AdminController::class, 'updateGuest'])->name('admin.guests.update');
    Route::delete('/admin/guests/{id}', [AdminController::class, 'deleteGuest'])->name('admin.guests.delete');
    Route::delete('/admin/messages/{id}', [AdminController::class, 'deleteMessage'])->name('admin.messages.delete');
    Route::get('/admin/guests/export', [AdminController::class, 'exportGuests'])->name('admin.guests.export');
    Route::get('/admin/guests/export-filtered', [AdminController::class, 'exportFiltered'])->name('admin.guests.export.filtered');
    Route::post('/guests/check', [AdminController::class, 'checkGuestExists'])->name('admin.guests.check');

    Route::get('/templates', [MessageTemplateController::class, 'index'])->name('admin.templates.index');
    Route::post('/templates', [MessageTemplateController::class, 'store'])->name('admin.templates.store');
    Route::put('/templates/{id}', [MessageTemplateController::class, 'update'])->name('admin.templates.update');
    Route::delete('/templates/{id}', [MessageTemplateController::class, 'destroy'])->name('admin.templates.destroy');
    Route::post('/templates/{id}/set-default', [MessageTemplateController::class, 'setDefault'])->name('admin.templates.set-default');
    Route::get('/templates/active', [MessageTemplateController::class, 'getActiveTemplates'])->name('admin.templates.active');
});

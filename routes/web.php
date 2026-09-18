<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\TemplateSettingController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingTemplateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route home default
Route::get('/', function () {
    return redirect()->route('wedding.public');
});

// Public link: renders the group flagged as "undangan utama".
Route::get('/invitation', [WeddingController::class, 'show'])->name('wedding.public');

// One invitation per group — the slug is the group's `event_key`, so the admin
// can create as many targeted invitations as needed (/p, /r, /keluarga-bride…).
Route::get('/{eventSlug}/invitation', [WeddingController::class, 'show'])
    ->where('eventSlug', '[A-Za-z0-9_-]+')
    ->name('wedding.show');

// Route untuk menyimpan RSVP
Route::post('/store-message', [WeddingController::class, 'storeMessage'])->name('wedding.store-message');

// Ucapan tamu (dipanggil daftar "Best Wishes" saat tamu men-scroll)
Route::get('/messages', [WeddingController::class, 'listMessages'])->name('wedding.messages');

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

    // Wedding Template Management Routes
    Route::get('/wedding-templates', [WeddingTemplateController::class, 'index'])->name('admin.wedding-templates.index');
    Route::post('/wedding-templates', [WeddingTemplateController::class, 'store'])->name('admin.wedding-templates.store');
    Route::put('/wedding-templates/{id}', [WeddingTemplateController::class, 'update'])->name('admin.wedding-templates.update');
    Route::delete('/wedding-templates/{id}', [WeddingTemplateController::class, 'destroy'])->name('admin.wedding-templates.destroy');
    Route::post('/wedding-templates/{id}/activate', [WeddingTemplateController::class, 'activate'])->name('admin.wedding-templates.activate');
    Route::get('/wedding-templates/{id}/preview', [WeddingTemplateController::class, 'preview'])->name('admin.wedding-templates.preview');

    // Template Settings Routes
    // The settings panel lives inside the dashboard (Settings tab) and is
    // loaded through the `embed` endpoint below — there is no standalone page.
    Route::get('/template-settings/embed', [TemplateSettingController::class, 'contentOnly'])->name('admin.template-settings.embed');
    Route::put('/template-settings', [TemplateSettingController::class, 'updateSettings'])->name('admin.template-settings.update');
    Route::put('/template-settings/events', [TemplateSettingController::class, 'updateEvents'])->name('admin.template-settings.events.update');
    Route::get('/template-settings/groups', [TemplateSettingController::class, 'listGroups'])->name('admin.template-settings.groups.index');
    Route::post('/template-settings/groups', [TemplateSettingController::class, 'storeGroup'])->name('admin.template-settings.groups.store');
    Route::put('/template-settings/gifts', [TemplateSettingController::class, 'updateGifts'])->name('admin.template-settings.gifts.update');
    Route::delete('/template-settings/groups', [TemplateSettingController::class, 'destroyGroup'])->name('admin.template-settings.groups.destroy');
    Route::post('/template-settings/upload-asset', [TemplateSettingController::class, 'uploadAsset'])->name('admin.template-settings.upload-asset');
    // Backsound (audio) memakai endpoint sendiri: validasi & pemrosesannya
    // berbeda dengan gambar.
    Route::post('/template-settings/upload-audio', [TemplateSettingController::class, 'uploadAudio'])->name('admin.template-settings.upload-audio');
    // Video pembuka (hero) juga punya endpoint sendiri: batas ukurannya jauh
    // lebih besar daripada gambar dan tidak ada proses resize.
    Route::post('/template-settings/upload-video', [TemplateSettingController::class, 'uploadVideo'])->name('admin.template-settings.upload-video');
    Route::delete('/template-settings/delete-asset', [TemplateSettingController::class, 'deleteAsset'])->name('admin.template-settings.delete-asset');
    Route::post('/template-settings/upload-gallery', [TemplateSettingController::class, 'uploadGallery'])->name('admin.template-settings.upload-gallery');
    // Gallery photos are addressed by their storage path (the numeric position
    // becomes stale after a drag-and-drop reorder).
    Route::put('/template-settings/gallery/caption', [TemplateSettingController::class, 'updateGalleryCaption'])->name('admin.template-settings.gallery.caption');
    Route::delete('/template-settings/gallery', [TemplateSettingController::class, 'deleteGalleryImage'])->name('admin.template-settings.gallery.delete');
    Route::put('/template-settings/gallery/reorder', [TemplateSettingController::class, 'reorderGallery'])->name('admin.template-settings.gallery.reorder');
});

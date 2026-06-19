<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\LogVisit;
use App\Models\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware(LogVisit::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/cv/download', function () {
    $profile = Profile::first();

    if (! $profile?->cv_path) {
        abort(404);
    }

    $profile->increment('cv_downloads');

    return Storage::disk('public')->download($profile->cv_path);
})->name('cv.download');

<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// PRD Section 11.1 — max 5 submissions per IP per 10 minutes.
Route::post('/inquiry', [InquiryController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('inquiry.store');

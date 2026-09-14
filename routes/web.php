<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// PRD Section 11.1 — max 5 submissions per IP per 10 minutes.
Route::post('/inquiry', [InquiryController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('inquiry.store');

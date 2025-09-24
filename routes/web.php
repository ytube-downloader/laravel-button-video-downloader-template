<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConverterController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Media converter routes
Route::get('/4k-video-converter', [ConverterController::class, 'fourKVideo'])->name('converter.4k-video');
Route::get('/audio-converter', [ConverterController::class, 'audioConverter'])->name('converter.audio');
Route::get('/batch-converter', [ConverterController::class, 'batchConverter'])->name('converter.batch');
Route::get('/audio-to-wav', [ConverterController::class, 'audioToWav'])->name('converter.audio-wav');
Route::get('/video-1080p-converter', [ConverterController::class, 'video1080p'])->name('converter.1080p');

// API routes for conversion (optional)
Route::post('/api/convert', [ConverterController::class, 'convert'])->name('api.convert');
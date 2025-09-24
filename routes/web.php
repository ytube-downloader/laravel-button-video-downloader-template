<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloaderController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Video downloader routes
Route::get('/4k-video-downloader', [DownloaderController::class, 'fourKVideo'])->name('downloader.4k-video');
Route::get('/video-to-mp3', [DownloaderController::class, 'videoToMp3'])->name('downloader.video-mp3');
Route::get('/playlist-downloader', [DownloaderController::class, 'playlistDownloader'])->name('downloader.playlist');
Route::get('/video-to-wav', [DownloaderController::class, 'videoToWav'])->name('downloader.video-wav');
Route::get('/video-1080p-downloader', [DownloaderController::class, 'video1080p'])->name('downloader.1080p');

// API routes for downloading
Route::post('/api/download', [DownloaderController::class, 'download'])->name('api.download');
Route::get('/api/video-info', [DownloaderController::class, 'getVideoInfo'])->name('api.video-info');
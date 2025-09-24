<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloaderController;
use App\Http\Middleware\RateLimitDownloads;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Video downloader routes
Route::get('/4k-video-downloader', [DownloaderController::class, 'fourKVideo'])->name('downloader.4k-video');
Route::get('/video-to-mp3', [DownloaderController::class, 'videoToMp3'])->name('downloader.video-mp3');
Route::get('/playlist-downloader', [DownloaderController::class, 'playlistDownloader'])->name('downloader.playlist');
Route::get('/video-to-wav', [DownloaderController::class, 'videoToWav'])->name('downloader.video-wav');
Route::get('/video-1080p-downloader', [DownloaderController::class, 'video1080p'])->name('downloader.1080p');

// Enhanced API routes with different rate limits
Route::prefix('api')->group(function () {
    
    // Info endpoints - higher rate limit (lighter operations)
    Route::middleware(['throttle:120,1'])->group(function () {
        Route::get('/video-info', [DownloaderController::class, 'getVideoInfo'])->name('api.video-info');
        Route::get('/audio-info', [DownloaderController::class, 'getAudioInfo'])->name('api.audio-info');
        Route::get('/supported-formats', [DownloaderController::class, 'getSupportedFormats'])->name('api.supported-formats');
        Route::get('/download-status', [DownloaderController::class, 'downloadStatus'])->name('api.download-status');
        Route::get('/audio-download-status', [DownloaderController::class, 'getAudioDownloadStatus'])->name('api.audio-download-status');
    });
    
    // Download endpoints - moderate rate limit (resource intensive)
    Route::middleware(['throttle:30,1'])->group(function () {
        Route::post('/download', [DownloaderController::class, 'download'])->name('api.download');
        Route::post('/download-mp3', [DownloaderController::class, 'downloadMp3'])->name('api.download-mp3');
        Route::post('/download-audio', [DownloaderController::class, 'downloadAudio'])->name('api.download-audio');
        Route::post('/download-clip', [DownloaderController::class, 'downloadClip'])->name('api.download-clip');
    });
    
    // Batch operations - stricter rate limit (very resource intensive)
    Route::middleware(['throttle:10,1'])->group(function () {
        Route::post('/batch-download', [DownloaderController::class, 'batchDownload'])->name('api.batch-download');
    });
});
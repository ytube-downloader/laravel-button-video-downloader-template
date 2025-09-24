<?php

// app/Http/Controllers/DownloaderController.php - Updated controller

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Services\EnhancedVideoDownloadService;
use App\Services\VideoDownloadApiClient;
use App\Http\Middleware\RateLimitDownloads;

class DownloaderController extends Controller
{
    private EnhancedVideoDownloadService $downloadService;

    public function __construct(EnhancedVideoDownloadService $downloadService)
    {
        $this->downloadService = $downloadService;
        
    }

    public function fourKVideo(): View
    {
        $pageData = [
            'title' => '4K Video Downloader',
            'subtitle' => 'Download videos in ultra-high 4K resolution',
            'description' => 'Get stunning 4K quality videos from your favorite platforms with our advanced downloading technology.',
            'supportedQualities' => ['4K (2160p)', '2K (1440p)', '1080p (Full HD)', '720p (HD)', '480p (SD)'],
            'pricing_note' => '4K downloads may incur additional charges for extended duration videos.'
        ];

        return view('downloaders.4k-video', compact('pageData'));
    }

    public function videoToMp3(): View
    {
        $pageData = [
            'title' => 'Video to MP3 Downloader',
            'subtitle' => 'Extract audio and download as MP3',
            'description' => 'Convert and download video audio as high-quality MP3 files from any supported platform.',
            'supportedFormats' => ['MP3', 'WAV', 'M4A', 'AAC', 'FLAC', 'OGG'],
            'audio_qualities' => [
                '96' => '96 kbps (Mobile)',
                '128' => '128 kbps (Standard)',
                '192' => '192 kbps (High)',
                '256' => '256 kbps (Premium)',
                '320' => '320 kbps (Maximum)'
            ]
        ];

        return view('downloaders.video-mp3', compact('pageData'));
    }

    public function playlistDownloader(): View
    {
        $pageData = [
            'title' => 'Playlist Downloader',
            'subtitle' => 'Download entire playlists with one click',
            'description' => 'Save time by downloading complete playlists, channels, or collections in batch.',
            'features' => [
                'Batch downloading', 
                'Queue management', 
                'Progress tracking', 
                'Format selection per video'
            ],
            'batch_limit' => 50 // Maximum videos per batch
        ];

        return view('downloaders.playlist', compact('pageData'));
    }

    public function videoToWav(): View
    {
        $pageData = [
            'title' => 'Video to WAV Downloader',
            'subtitle' => 'Download video audio as high-quality WAV files',
            'description' => 'Extract and download video audio in uncompressed WAV format for the best quality.',
            'benefits' => ['Lossless audio quality', 'Professional standard', 'Universal compatibility'],
            'sample_rates' => ['44100', '48000', '96000'],
            'bit_depths' => ['16', '24', '32']
        ];

        return view('downloaders.video-wav', compact('pageData'));
    }

    public function video1080p(): View
    {
        $pageData = [
            'title' => '1080p Video Downloader',
            'subtitle' => 'Download videos in Full HD 1080p resolution',
            'description' => 'Get crisp Full HD quality videos perfect for any device or screen.',
            'features' => ['Full HD quality', 'Fast downloading', 'Multiple format support']
        ];

        return view('downloaders.1080p', compact('pageData'));
    }

    /**
     * Get comprehensive video information using the external API
     */
    public function getVideoInfo(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            $videoInfo = $this->downloadService->getVideoInfo($request->input('url'));
            
            return response()->json($videoInfo);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve video information: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Start download process using external API
     */
    public function download(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url',
            'quality' => 'required|string|in:4k,8k,1440p,1080p,720p,480p,360p',
            'format' => 'required|string|in:mp4,webm,mp3,m4a,wav,flac,aac,ogg',
            'audio_quality' => 'sometimes|integer|in:96,128,192,256,320',
            'audio_language' => 'sometimes|string|size:2' // ISO 639-1 language codes
        ]);

        try {
            $url = $request->input('url');
            $quality = $request->input('quality');
            $format = $request->input('format');
            $audioQuality = $request->input('audio_quality', 128);
            $audioLanguage = $request->input('audio_language');

            // Determine download type based on format
            $isAudio = in_array($format, ['mp3', 'm4a', 'wav', 'flac', 'aac', 'ogg']);

            $options = [];
            if ($audioLanguage) {
                $options['audio_language'] = $audioLanguage;
            }

            if ($isAudio) {
                // Audio extraction
                if ($format === 'wav') {
                    $result = $this->downloadService->extractWAV($url, $audioQuality);
                } elseif ($format === 'flac') {
                    $result = $this->downloadService->extractFLAC($url);
                } else {
                    $result = $this->downloadService->extractAudio($url, $format, $audioQuality, $options);
                }
            } else {
                // Video download
                if ($quality === '4k') {
                    $result = $this->downloadService->download4K($url, $options);
                } else {
                    $result = $this->downloadService->downloadVideo($url, $quality, $options);
                }
            }

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Download started successfully',
                    'download_id' => $result['download_id'],
                    'type' => $result['type'],
                    'format_name' => $result['format_name'] ?? $format,
                    'info' => $result['info'] ?? null,
                    'pricing' => $result['pricing'] ?? null,
                    'requires_payment' => $result['requires_payment'] ?? false
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get download status and progress
     */
    public function downloadStatus(Request $request): JsonResponse
    {
        $request->validate([
            'download_id' => 'required|string'
        ]);

        try {
            $result = $this->downloadService->getDownloadStatus($request->input('download_id'));
            
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get download status: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Batch download multiple videos
     */
    public function batchDownload(Request $request): JsonResponse
    {
        $request->validate([
            'urls' => 'required|array|min:1|max:50',
            'urls.*' => 'required|url',
            'quality' => 'required|string|in:4k,1440p,1080p,720p,480p,360p',
            'format' => 'sometimes|string|in:mp4,webm',
            'audio_language' => 'sometimes|string|size:2'
        ]);

        try {
            $urls = $request->input('urls');
            $quality = $request->input('quality');
            $options = [];

            if ($request->has('audio_language')) {
                $options['audio_language'] = $request->input('audio_language');
            }

            $result = $this->downloadService->batchDownload($urls, $quality, $options);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Batch download started' : 'Some downloads failed',
                'summary' => $result['summary'],
                'results' => $result['results']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Batch download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported formats for a specific video
     */
    public function getSupportedFormats(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            $apiClient = app(VideoDownloadApiClient::class);
            $result = $apiClient->getSupportedFormats($request->input('url'));

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get supported formats: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Download video with specific time range (clip)
     */
    public function downloadClip(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url',
            'quality' => 'required|string|in:1080p,720p,480p,360p',
            'start_time' => 'required|integer|min:0',
            'end_time' => 'required|integer|min:1',
            'format' => 'sometimes|string|in:mp4,webm'
        ]);

        try {
            $apiClient = app(VideoDownloadApiClient::class);
            
            $url = $request->input('url');
            $quality = $request->input('quality');
            $startTime = $request->input('start_time');
            $endTime = $request->input('end_time');

            // Validate time range
            if ($endTime <= $startTime) {
                return response()->json([
                    'success' => false,
                    'error' => 'End time must be greater than start time'
                ], 400);
            }

            if (($endTime - $startTime) > 3600) { // 1 hour limit
                return response()->json([
                    'success' => false,
                    'error' => 'Clip duration cannot exceed 1 hour'
                ], 400);
            }

            $formatMapping = [
                '360p' => VideoDownloadApiClient::FORMAT_360P,
                '480p' => VideoDownloadApiClient::FORMAT_480P,
                '720p' => VideoDownloadApiClient::FORMAT_720P,
                '1080p' => VideoDownloadApiClient::FORMAT_1080P,
            ];

            $result = $apiClient->downloadClip(
                $url,
                $formatMapping[$quality],
                $startTime,
                $endTime
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Clip download started',
                    'download_id' => $result['download_id'],
                    'clip_duration' => $endTime - $startTime,
                    'info' => $result['info'] ?? null
                ]);
            }

            return response()->json($result, 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Clip download failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
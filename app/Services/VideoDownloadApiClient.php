<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class VideoDownloadApiClient
{
    private const BASE_URL = 'https://p.savenow.to/ajax/download.php';
    private const TIMEOUT = 120; // 2 minutes timeout for large downloads
    
    private string $apiKey;
    private array $defaultOptions;

    /**
     * Format constants for different video/audio qualities
     */
    public const FORMAT_MP3 = 1;
    public const FORMAT_M4A = 2;
    public const FORMAT_WEBM_AUDIO = 3;
    public const FORMAT_AAC = 4;
    public const FORMAT_FLAC = 5;
    public const FORMAT_WAV = 6;
    public const FORMAT_OGG = 7;
    
    public const FORMAT_360P = '360';
    public const FORMAT_480P = '480';
    public const FORMAT_720P = '720';
    public const FORMAT_1080P = '1080';
    public const FORMAT_1440P = '1440';
    public const FORMAT_4K = '2160';
    public const FORMAT_8K = '4320';
    
    /**
     * Audio quality bitrates
     */
    public const AUDIO_QUALITY_LOW = 96;
    public const AUDIO_QUALITY_STANDARD = 128;
    public const AUDIO_QUALITY_HIGH = 192;
    public const AUDIO_QUALITY_PREMIUM = 256;
    public const AUDIO_QUALITY_MAXIMUM = 320;

    public function __construct(string $apiKey = '', array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->defaultOptions = array_merge([
            'timeout' => self::TIMEOUT,
            'retry_times' => 3,
            'retry_delay' => 1000, // milliseconds
        ], $options);
    }

    /**
     * Get video information without downloading
     */
    public function getVideoInfo(string $videoUrl): array
    {
        try {
            $response = $this->makeRequest([
                'url' => $videoUrl,
                'format' => self::FORMAT_1080P,
                'add_info' => 1,
                'info_only' => 1 // Custom parameter to only get info
            ]);

            return [
                'success' => true,
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Download video in specified quality
     */
    public function downloadVideo(
        string $videoUrl, 
        string $format = self::FORMAT_1080P, 
        array $options = []
    ): array {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => $format,
            'add_info' => 1,
        ], $options);

        return $this->initiateDownload($params, 'video');
    }

    /**
     * Extract audio from video in specified format
     */
    public function extractAudio(
        string $videoUrl, 
        int $format = self::FORMAT_MP3, 
        int $audioQuality = self::AUDIO_QUALITY_STANDARD,
        array $options = []
    ): array {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => $format,
            'audio_quality' => $audioQuality,
            'add_info' => 1,
        ], $options);

        return $this->initiateDownload($params, 'audio');
    }

    /**
     * Download 4K video with extended duration support
     */
    public function download4K(string $videoUrl, array $options = []): array
    {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => self::FORMAT_4K,
            'add_info' => 1,
            'allow_extended_duration' => 1,
        ], $options);

        return $this->initiateDownload($params, '4k_video');
    }

    /**
     * Download 8K video (premium feature)
     */
    public function download8K(string $videoUrl, array $options = []): array
    {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => self::FORMAT_8K,
            'add_info' => 1,
            'allow_extended_duration' => 1,
        ], $options);

        return $this->initiateDownload($params, '8k_video');
    }

    /**
     * Extract high-quality WAV audio
     */
    public function extractWAV(
        string $videoUrl, 
        int $audioQuality = self::AUDIO_QUALITY_PREMIUM,
        array $options = []
    ): array {
        return $this->extractAudio($videoUrl, self::FORMAT_WAV, $audioQuality, $options);
    }

    /**
     * Extract FLAC audio (lossless)
     */
    public function extractFLAC(string $videoUrl, array $options = []): array
    {
        return $this->extractAudio($videoUrl, self::FORMAT_FLAC, 0, $options); // FLAC doesn't use bitrate
    }

    /**
     * Download with specific audio language
     */
    public function downloadWithLanguage(
        string $videoUrl,
        string $format,
        string $audioLanguage,
        array $options = []
    ): array {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => $format,
            'audio_language' => $audioLanguage,
            'add_info' => 1,
        ], $options);

        return $this->initiateDownload($params, 'video_with_language');
    }

    /**
     * Download video with time range (clip)
     */
    public function downloadClip(
        string $videoUrl,
        string $format,
        int $startTime,
        int $endTime,
        array $options = []
    ): array {
        $params = array_merge([
            'url' => $videoUrl,
            'format' => $format,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'add_info' => 1,
        ], $options);

        return $this->initiateDownload($params, 'clip');
    }

    /**
     * Get download progress/status by download ID
     */
    public function getDownloadStatus(string $downloadId): array
    {
        try {
            $response = $this->makeRequest([
                'action' => 'status',
                'id' => $downloadId
            ]);

            return [
                'success' => true,
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get supported formats for a specific video URL
     */
    public function getSupportedFormats(string $videoUrl): array
    {
        try {
            $response = $this->makeRequest([
                'url' => $videoUrl,
                'action' => 'get_formats',
                'add_info' => 1
            ]);

            return [
                'success' => true,
                'formats' => $response['available_formats'] ?? [],
                'info' => $response['info'] ?? null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Batch download multiple videos
     */
    public function batchDownload(array $videoUrls, string $format, array $options = []): array
    {
        $results = [];
        $successful = 0;
        $failed = 0;

        foreach ($videoUrls as $index => $videoUrl) {
            try {
                $result = $this->downloadVideo($videoUrl, $format, $options);
                $results[] = [
                    'index' => $index,
                    'url' => $videoUrl,
                    'result' => $result
                ];

                if ($result['success']) {
                    $successful++;
                } else {
                    $failed++;
                }

                // Add delay between requests to avoid rate limiting
                if ($index < count($videoUrls) - 1) {
                    usleep(500000); // 500ms delay
                }
            } catch (\Exception $e) {
                $results[] = [
                    'index' => $index,
                    'url' => $videoUrl,
                    'result' => ['success' => false, 'error' => $e->getMessage()]
                ];
                $failed++;
            }
        }

        return [
            'success' => $failed === 0,
            'summary' => [
                'total' => count($videoUrls),
                'successful' => $successful,
                'failed' => $failed
            ],
            'results' => $results
        ];
    }

    /**
     * Private method to initiate download with proper error handling
     */
    private function initiateDownload(array $params, string $downloadType): array
    {
        try {
            $response = $this->makeRequest($params);

            $result = [
                'success' => true,
                'download_id' => $response['id'] ?? null,
                'download_type' => $downloadType,
                'info' => $response['info'] ?? null,
                'content' => $response['content'] ?? null,
            ];

            // Handle extended duration pricing if present
            if (isset($response['extended_duration'])) {
                $result['extended_duration'] = $response['extended_duration'];
                $result['pricing'] = [
                    'multiplier' => $response['extended_duration']['multiplier'] ?? 1,
                    'original_price' => $response['extended_duration']['original_price'] ?? 0,
                    'final_price' => $response['extended_duration']['final_price'] ?? 0
                ];
            }

            // Log successful download initiation
            Log::info('Video download initiated', [
                'download_id' => $result['download_id'],
                'type' => $downloadType,
                'url' => $params['url'] ?? 'unknown'
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Video download failed', [
                'error' => $e->getMessage(),
                'params' => $params,
                'type' => $downloadType
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'download_type' => $downloadType
            ];
        }
    }

    /**
     * Make HTTP request to the API with retry logic
     */
    private function makeRequest(array $params): array
    {
        $params['apikey'] = $this->apiKey;
        
        $attempt = 0;
        $maxAttempts = $this->defaultOptions['retry_times'];
        
        while ($attempt < $maxAttempts) {
            try {
                $response = Http::timeout($this->defaultOptions['timeout'])
                    ->get(self::BASE_URL, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (!isset($data['success']) || !$data['success']) {
                        throw new \Exception($data['error'] ?? 'API request failed');
                    }
                    
                    return $data;
                }

                throw new \Exception("HTTP {$response->status()}: {$response->body()}");

            } catch (ConnectionException $e) {
                $attempt++;
                if ($attempt >= $maxAttempts) {
                    throw new \Exception("Connection failed after {$maxAttempts} attempts: " . $e->getMessage());
                }
                
                // Wait before retrying
                usleep($this->defaultOptions['retry_delay'] * 1000);
                
            } catch (RequestException $e) {
                throw new \Exception("Request failed: " . $e->getMessage());
            }
        }

        throw new \Exception("Max retry attempts reached");
    }

    /**
     * Validate video URL format
     */
    public function isValidVideoUrl(string $url): bool
    {
        $supportedDomains = [
            'youtube.com',
            'youtu.be',
            'vimeo.com',
            'dailymotion.com',
            'facebook.com',
            'instagram.com',
            'tiktok.com',
            'twitter.com',
            'twitch.tv'
        ];

        foreach ($supportedDomains as $domain) {
            if (strpos($url, $domain) !== false) {
                return filter_var($url, FILTER_VALIDATE_URL) !== false;
            }
        }

        return false;
    }

    /**
     * Get human-readable format name
     */
    public function getFormatName($format): string
    {
        $formatNames = [
            self::FORMAT_MP3 => 'MP3 Audio',
            self::FORMAT_M4A => 'M4A Audio',
            self::FORMAT_WEBM_AUDIO => 'WebM Audio',
            self::FORMAT_AAC => 'AAC Audio',
            self::FORMAT_FLAC => 'FLAC Audio (Lossless)',
            self::FORMAT_WAV => 'WAV Audio (Uncompressed)',
            self::FORMAT_OGG => 'OGG Audio',
            self::FORMAT_360P => '360p Video',
            self::FORMAT_480P => '480p Video',
            self::FORMAT_720P => '720p HD Video',
            self::FORMAT_1080P => '1080p Full HD Video',
            self::FORMAT_1440P => '1440p 2K Video',
            self::FORMAT_4K => '4K Ultra HD Video',
            self::FORMAT_8K => '8K Ultra HD Video'
        ];

        return $formatNames[$format] ?? "Format {$format}";
    }

    /**
     * Get estimated file size based on duration and quality
     */
    public function estimateFileSize(int $durationSeconds, string $format): array
    {
        // Rough estimates in MB per minute for different formats
        $sizesPerMinute = [
            self::FORMAT_MP3 => 1.0,        // ~128kbps
            self::FORMAT_M4A => 0.8,
            self::FORMAT_AAC => 0.9,
            self::FORMAT_WAV => 10.0,       // Uncompressed
            self::FORMAT_FLAC => 5.0,       // Lossless compression
            self::FORMAT_360P => 5.0,
            self::FORMAT_480P => 12.0,
            self::FORMAT_720P => 25.0,
            self::FORMAT_1080P => 50.0,
            self::FORMAT_1440P => 80.0,
            self::FORMAT_4K => 150.0,
            self::FORMAT_8K => 400.0
        ];

        $minutes = $durationSeconds / 60;
        $sizePerMinute = $sizesPerMinute[$format] ?? 25.0;
        $estimatedMB = $minutes * $sizePerMinute;

        return [
            'estimated_mb' => round($estimatedMB, 1),
            'estimated_gb' => round($estimatedMB / 1024, 2),
            'formatted' => $estimatedMB > 1024 
                ? round($estimatedMB / 1024, 2) . ' GB'
                : round($estimatedMB, 1) . ' MB'
        ];
    }
}
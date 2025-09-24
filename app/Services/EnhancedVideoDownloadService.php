<?php

namespace App\Services;

use App\Models\Download;
use App\Services\VideoDownloadApiClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EnhancedVideoDownloadService
{
    private VideoDownloadApiClient $apiClient;
    private array $formatMapping;

    public function __construct()
    {
        $this->apiClient = new VideoDownloadApiClient(
            config('services.video_download_api.key'),
            [
                'timeout' => config('services.video_download_api.timeout', 120),
                'retry_times' => config('services.video_download_api.retry_times', 3)
            ]
        );

        $this->formatMapping = [
            'mp3' => VideoDownloadApiClient::FORMAT_MP3,
            'm4a' => VideoDownloadApiClient::FORMAT_M4A,
            'wav' => VideoDownloadApiClient::FORMAT_WAV,
            'flac' => VideoDownloadApiClient::FORMAT_FLAC,
            'aac' => VideoDownloadApiClient::FORMAT_AAC,
            'ogg' => VideoDownloadApiClient::FORMAT_OGG,
            '360p' => VideoDownloadApiClient::FORMAT_360P,
            '480p' => VideoDownloadApiClient::FORMAT_480P,
            '720p' => VideoDownloadApiClient::FORMAT_720P,
            '1080p' => VideoDownloadApiClient::FORMAT_1080P,
            '1440p' => VideoDownloadApiClient::FORMAT_1440P,
            '4k' => VideoDownloadApiClient::FORMAT_4K,
            '8k' => VideoDownloadApiClient::FORMAT_8K,
        ];
    }

    /**
     * Create download using the external API
     */
    public function createDownload(array $data, string $ipAddress): Download
    {
        // Create database record first
        $download = Download::create([
            'download_id' => Str::uuid(),
            'video_url' => $data['url'],
            'quality' => $data['quality'],
            'format' => $data['format'],
            'status' => 'pending',
            'ip_address' => $ipAddress,
            'metadata' => [
                'user_agent' => request()->header('User-Agent'),
                'requested_at' => now()->toISOString(),
                'api_client' => 'video-download-api.com'
            ]
        ]);

        // Process download asynchronously
        $this->processDownloadWithApi($download);

        return $download;
    }

    /**
     * Get comprehensive video information
     */
    public function getVideoInfo(string $url): array
    {
        // Check cache first
        $cacheKey = 'video_info_' . md5($url);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // Validate URL first
            if (!$this->apiClient->isValidVideoUrl($url)) {
                throw new \InvalidArgumentException('Invalid or unsupported video URL');
            }

            $response = $this->apiClient->getVideoInfo($url);

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Failed to get video information');
            }

            $videoInfo = $this->formatVideoInfo($response['data']);

            // Cache for 1 hour
            Cache::put($cacheKey, $videoInfo, 3600);

            return $videoInfo;

        } catch (\Exception $e) {
            Log::error('Failed to get video info', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Download video in specified quality
     */
    public function downloadVideo(string $url, string $quality = '1080p', array $options = []): array
    {
        try {
            $format = $this->formatMapping[$quality] ?? VideoDownloadApiClient::FORMAT_1080P;
            
            $response = $this->apiClient->downloadVideo($url, $format, $options);

            if ($response['success']) {
                return [
                    'success' => true,
                    'download_id' => $response['download_id'],
                    'info' => $response['info'],
                    'type' => 'video',
                    'quality' => $quality,
                    'format_name' => $this->apiClient->getFormatName($format)
                ];
            }

            throw new \Exception($response['error'] ?? 'Download failed');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Extract audio in specified format
     */
    public function extractAudio(
        string $url, 
        string $format = 'mp3', 
        int $quality = VideoDownloadApiClient::AUDIO_QUALITY_STANDARD,
        array $options = []
    ): array {
        try {
            $formatCode = $this->formatMapping[$format] ?? VideoDownloadApiClient::FORMAT_MP3;
            
            $response = $this->apiClient->extractAudio($url, $formatCode, $quality, $options);

            if ($response['success']) {
                return [
                    'success' => true,
                    'download_id' => $response['download_id'],
                    'info' => $response['info'],
                    'type' => 'audio',
                    'format' => $format,
                    'quality' => $quality . 'kbps',
                    'format_name' => $this->apiClient->getFormatName($formatCode)
                ];
            }

            throw new \Exception($response['error'] ?? 'Audio extraction failed');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Download 4K video with enhanced options
     */
    public function download4K(string $url, array $options = []): array
    {
        try {
            $response = $this->apiClient->download4K($url, $options);

            if ($response['success']) {
                $result = [
                    'success' => true,
                    'download_id' => $response['download_id'],
                    'info' => $response['info'],
                    'type' => '4k_video',
                    'quality' => '4K',
                    'format_name' => '4K Ultra HD Video'
                ];

                // Include pricing information if available
                if (isset($response['pricing'])) {
                    $result['pricing'] = $response['pricing'];
                    $result['requires_payment'] = $response['pricing']['final_price'] > 0;
                }

                return $result;
            }

            throw new \Exception($response['error'] ?? '4K download failed');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Extract high-quality WAV audio
     */
    public function extractWAV(string $url, int $quality = VideoDownloadApiClient::AUDIO_QUALITY_PREMIUM): array
    {
        try {
            $response = $this->apiClient->extractWAV($url, $quality);

            if ($response['success']) {
                return [
                    'success' => true,
                    'download_id' => $response['download_id'],
                    'info' => $response['info'],
                    'type' => 'wav_audio',
                    'format' => 'wav',
                    'quality' => 'Uncompressed',
                    'format_name' => 'WAV Audio (Uncompressed)'
                ];
            }

            throw new \Exception($response['error'] ?? 'WAV extraction failed');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Extract lossless FLAC audio
     */
    public function extractFLAC(string $url): array
    {
        try {
            $response = $this->apiClient->extractFLAC($url);

            if ($response['success']) {
                return [
                    'success' => true,
                    'download_id' => $response['download_id'],
                    'info' => $response['info'],
                    'type' => 'flac_audio',
                    'format' => 'flac',
                    'quality' => 'Lossless',
                    'format_name' => 'FLAC Audio (Lossless)'
                ];
            }

            throw new \Exception($response['error'] ?? 'FLAC extraction failed');

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
    public function batchDownload(array $videoUrls, string $quality = '1080p', array $options = []): array
    {
        try {
            $format = $this->formatMapping[$quality] ?? VideoDownloadApiClient::FORMAT_1080P;
            
            $response = $this->apiClient->batchDownload($videoUrls, $format, $options);

            // Store batch download record
            foreach ($response['results'] as $result) {
                if ($result['result']['success']) {
                    Download::create([
                        'download_id' => $result['result']['download_id'] ?? Str::uuid(),
                        'video_url' => $result['url'],
                        'quality' => $quality,
                        'format' => 'mp4',
                        'status' => 'processing',
                        'ip_address' => request()->ip(),
                        'metadata' => [
                            'batch_download' => true,
                            'batch_index' => $result['index'],
                            'api_client' => 'video-download-api.com'
                        ]
                    ]);
                }
            }

            return $response;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get download status from external API
     */
    public function getDownloadStatus(string $downloadId): array
    {
        try {
            $response = $this->apiClient->getDownloadStatus($downloadId);

            if ($response['success']) {
                // Update local database record
                $download = Download::where('download_id', $downloadId)->first();
                if ($download) {
                    $this->updateLocalDownloadStatus($download, $response['data']);
                }

                return $response;
            }

            throw new \Exception($response['error'] ?? 'Failed to get download status');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process download using external API
     */
    private function processDownloadWithApi(Download $download): void
    {
        try {
            $download->update([
                'status' => 'processing',
                'started_at' => now(),
            ]);

            // Map format for API
            $apiFormat = $this->formatMapping[$download->format] ?? VideoDownloadApiClient::FORMAT_1080P;

            // Determine if it's audio or video
            $isAudio = in_array($download->format, ['mp3', 'm4a', 'wav', 'flac', 'aac', 'ogg']);

            if ($isAudio) {
                $response = $this->apiClient->extractAudio(
                    $download->video_url, 
                    $apiFormat, 
                    VideoDownloadApiClient::AUDIO_QUALITY_STANDARD
                );
            } else {
                $response = $this->apiClient->downloadVideo($download->video_url, $apiFormat);
            }

            if ($response['success']) {
                $download->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'video_title' => $response['info']['title'] ?? 'Unknown Title',
                    'video_duration' => $this->extractDuration($response['info'] ?? []),
                    'metadata' => array_merge($download->metadata ?? [], [
                        'api_download_id' => $response['download_id'],
                        'api_response' => $response
                    ])
                ]);

                Log::info('Download completed via API', [
                    'download_id' => $download->download_id,
                    'api_download_id' => $response['download_id']
                ]);

            } else {
                throw new \Exception($response['error'] ?? 'API download failed');
            }

        } catch (\Exception $e) {
            $download->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            Log::error('Download failed via API', [
                'download_id' => $download->download_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format video information from API response
     */
    private function formatVideoInfo(array $apiData): array
    {
        $info = $apiData['info'] ?? [];

        return [
            'success' => true,
            'video_info' => [
                'title' => $info['title'] ?? 'Unknown Title',
                'duration' => $this->extractDuration($info),
                'thumbnail' => $info['image'] ?? $info['thumbnail'] ?? null,
                'views' => $info['views'] ?? null,
                'upload_date' => $info['upload_date'] ?? null,
                'uploader' => $info['uploader'] ?? $info['author'] ?? null,
                'description' => $info['description'] ?? null,
                'available_qualities' => $this->getAvailableQualities(),
                'available_formats' => $this->getAvailableFormats(),
                'estimated_sizes' => $this->calculateEstimatedSizes($info)
            ]
        ];
    }

    /**
     * Extract duration from video info
     */
    private function extractDuration(array $info): string
    {
        if (isset($info['duration'])) {
            return $info['duration'];
        }

        if (isset($info['duration_seconds'])) {
            $seconds = (int) $info['duration_seconds'];
            return gmdate($seconds >= 3600 ? 'H:i:s' : 'i:s', $seconds);
        }

        return 'Unknown';
    }

    /**
     * Get available video qualities
     */
    private function getAvailableQualities(): array
    {
        return ['4K', '1440p', '1080p', '720p', '480p', '360p'];
    }

    /**
     * Get available formats
     */
    private function getAvailableFormats(): array
    {
        return ['MP4', 'WEBM', 'MP3', 'WAV', 'M4A', 'AAC', 'FLAC', 'OGG'];
    }

    /**
     * Calculate estimated file sizes for different formats
     */
    private function calculateEstimatedSizes(array $info): array
    {
        $durationSeconds = $info['duration_seconds'] ?? 180; // Default 3 minutes
        $sizes = [];

        foreach ($this->formatMapping as $format => $apiFormat) {
            $estimation = $this->apiClient->estimateFileSize($durationSeconds, $apiFormat);
            $sizes[$format] = $estimation['formatted'];
        }

        return $sizes;
    }

    /**
     * Update local download status based on API response
     */
    private function updateLocalDownloadStatus(Download $download, array $apiData): void
    {
        $statusMapping = [
            'completed' => 'completed',
            'processing' => 'processing',
            'failed' => 'failed',
            'pending' => 'processing'
        ];

        $apiStatus = $apiData['status'] ?? 'processing';
        $localStatus = $statusMapping[$apiStatus] ?? 'processing';

        $updateData = ['status' => $localStatus];

        if ($localStatus === 'completed') {
            $updateData['completed_at'] = now();
            $updateData['download_path'] = $apiData['download_url'] ?? null;
        } elseif ($localStatus === 'failed') {
            $updateData['error_message'] = $apiData['error'] ?? 'Download failed';
            $updateData['completed_at'] = now();
        }

        $download->update($updateData);
    }
}
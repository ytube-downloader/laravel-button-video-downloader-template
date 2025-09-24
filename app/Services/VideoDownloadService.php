<?php

namespace App\Services;

use App\Models\Download;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VideoDownloadService
{
    public function createDownload(array $data, string $ipAddress): Download
    {
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
            ]
        ]);

        // Queue the download processing
        $this->processDownload($download);

        return $download;
    }

    public function getVideoInfo(string $url): array
    {
        // In a real implementation, you would use yt-dlp or similar
        // to extract actual video information from the URL
        
        // Simulated response for demo
        return [
            'success' => true,
            'video_info' => [
                'title' => $this->generateTitleFromUrl($url),
                'duration' => $this->generateRandomDuration(),
                'thumbnail' => 'https://via.placeholder.com/480x360',
                'views' => number_format(rand(1000, 10000000)) . ' views',
                'upload_date' => $this->generateRandomDate(),
                'available_qualities' => ['4K', '1080p', '720p', '480p', '360p'],
                'available_formats' => ['MP4', 'WEBM', 'MP3', 'WAV', 'M4A']
            ]
        ];
    }

    protected function processDownload(Download $download): void
    {
        try {
            $download->update([
                'status' => 'processing',
                'started_at' => now(),
            ]);

            // Extract video information
            $videoInfo = $this->extractVideoInfo($download->video_url);
            
            $download->update([
                'video_title' => $videoInfo['title'] ?? 'Unknown Title',
                'video_duration' => $videoInfo['duration'] ?? 'Unknown',
            ]);

            // Simulate download processing
            $this->simulateDownloadProcess($download);

            $downloadPath = 'downloads/' . $download->download_id . '.' . $download->format;

            $download->update([
                'status' => 'completed',
                'download_path' => $downloadPath,
                'file_size' => rand(1048576, 104857600), // 1MB to 100MB
                'completed_at' => now(),
            ]);

        } catch (\Exception $e) {
            $download->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);
        }
    }

    protected function extractVideoInfo(string $url): array
    {
        // In production, use yt-dlp or similar tool
        return [
            'title' => $this->generateTitleFromUrl($url),
            'duration' => $this->generateRandomDuration(),
        ];
    }

    protected function simulateDownloadProcess(Download $download): void
    {
        // Simulate processing time based on quality
        $processingTime = match($download->quality) {
            '4k' => rand(30, 60),
            '1080p' => rand(15, 30),
            '720p' => rand(10, 20),
            default => rand(5, 10)
        };

        sleep($processingTime);
    }

    public function getDownloadStatus(string $downloadId): ?Download
    {
        return Download::where('download_id', $downloadId)->first();
    }

    public function getRecentDownloads(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Download::latest()
            ->limit($limit)
            ->get(['download_id', 'video_title', 'quality', 'format', 'status', 'created_at']);
    }

    protected function generateTitleFromUrl(string $url): string
    {
        $titles = [
            'Amazing Technology Demo',
            'Educational Content Video',
            'Entertainment Highlights',
            'Tutorial and Guide',
            'Music Performance',
            'News and Updates',
            'Documentary Excerpt',
            'Creative Showcase'
        ];

        return $titles[array_rand($titles)] . ' - ' . date('Y');
    }

    protected function generateRandomDuration(): string
    {
        $minutes = rand(1, 60);
        $seconds = rand(0, 59);
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    protected function generateRandomDate(): string
    {
        $days = rand(1, 30);
        return $days === 1 ? '1 day ago' : $days . ' days ago';
    }
}
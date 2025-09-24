<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroData = [
            'title' => 'Video Downloader',
            'subtitle' => 'Download videos from popular social platforms',
            'description' => 'Fast, secure, and reliable video downloading tool supporting multiple formats and qualities.'
        ];

        $features = [
            [
                'icon' => 'video',
                'title' => 'HD Video Download',
                'description' => 'Download videos in various qualities including 4K, 1080p, 720p, and more.'
            ],
            [
                'icon' => 'audio',
                'title' => 'Audio Extraction',
                'description' => 'Extract audio from videos and save as MP3, WAV, or other audio formats.'
            ],
            [
                'icon' => 'playlist',
                'title' => 'Playlist Support',
                'description' => 'Download entire playlists with a single click to save time and effort.'
            ],
            [
                'icon' => 'fast',
                'title' => 'Fast Processing',
                'description' => 'Quick downloads with high-speed servers and optimized processing.'
            ]
        ];

        $partners = [
            'VideoTech', 'MediaStream', 'DownloadPro', 'StreamSaver', 'QuickVid'
        ];

        $faqs = [
            [
                'question' => 'What video platforms are supported?',
                'answer' => 'We support downloads from all major video sharing platforms and social media sites.'
            ],
            [
                'question' => 'Is the downloading process secure?',
                'answer' => 'Yes, all downloads are processed securely and we don\'t store any personal information.'
            ],
            [
                'question' => 'How long does downloading take?',
                'answer' => 'Download time depends on video length and quality, but most downloads complete within minutes.'
            ],
            [
                'question' => 'Are there any limits on video length?',
                'answer' => 'Free users can download videos up to 2 hours. Premium users have no limits.'
            ]
        ];

        return view('home', compact('heroData', 'features', 'partners', 'faqs'));
    }
}
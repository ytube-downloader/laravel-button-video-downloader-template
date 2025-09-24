<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroData = [
            'title' => 'Media File Converter',
            'subtitle' => 'Convert your media files to any format quickly and easily',
            'description' => 'Fast, secure, and reliable media conversion tool supporting multiple formats.'
        ];

        $features = [
            [
                'icon' => 'video',
                'title' => 'Video Conversion',
                'description' => 'Convert videos to various formats including MP4, AVI, MOV, and more.'
            ],
            [
                'icon' => 'audio',
                'title' => 'Audio Conversion',
                'description' => 'Transform audio files to MP3, WAV, FLAC, and other popular formats.'
            ],
            [
                'icon' => 'batch',
                'title' => 'Batch Processing',
                'description' => 'Process multiple files at once to save time and effort.'
            ],
            [
                'icon' => 'quality',
                'title' => 'High Quality',
                'description' => 'Maintain excellent quality during conversion with advanced algorithms.'
            ]
        ];

        $partners = [
            'TechCorp', 'MediaPro', 'DigitalWave', 'StreamTech', 'ConvertMax'
        ];

        $faqs = [
            [
                'question' => 'What file formats are supported?',
                'answer' => 'We support all major video and audio formats including MP4, AVI, MOV, MP3, WAV, FLAC, and many more.'
            ],
            [
                'question' => 'Is the conversion process secure?',
                'answer' => 'Yes, all files are processed securely and deleted from our servers after conversion.'
            ],
            [
                'question' => 'How long does conversion take?',
                'answer' => 'Conversion time depends on file size and format, but most conversions complete within minutes.'
            ],
            [
                'question' => 'Is there a file size limit?',
                'answer' => 'Free users can convert files up to 100MB. Premium users have higher limits.'
            ]
        ];

        return view('home', compact('heroData', 'features', 'partners', 'faqs'));
    }
}
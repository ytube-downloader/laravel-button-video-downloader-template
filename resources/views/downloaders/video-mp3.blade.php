@extends('layouts.app')

@section('title', 'Video to MP3 Downloader - Extract Audio Online')
@section('description', 'Extract and download audio from videos as MP3 files. High-quality audio extraction from popular video platforms.')

@section('content')
<div class="container mx-auto px-5 py-12">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold theme-heading dark:theme-heading mb-4">
            {{ $pageData['title'] }}
        </h1>
        <p class="text-xl theme-base dark:theme-base mb-2">
            {{ $pageData['subtitle'] }}
        </p>
        <p class="text-lg theme-base dark:theme-base max-w-2xl mx-auto">
            {{ $pageData['description'] }}
        </p>
    </div>

    <!-- Audio Download Interface -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Header with Audio Icon -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white text-center">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-2xl">→</span>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold">Video to Audio Converter</h2>
                <p class="opacity-90">Extract high-quality audio from any video</p>
            </div>

            <div class="p-8" x-data="audioDownloader()">
                <!-- URL Input -->
                <div class="mb-8">
                    <div class="url-input p-1">
                        <div class="flex">
                            <input 
                                x-model="videoUrl"
                                type="url" 
                                placeholder="Paste video URL to extract audio..."
                                class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none theme-heading dark:theme-heading"
                                @input="validateUrl()"
                            >
                            <button 
                                @click="analyzeVideo()"
                                :disabled="!isValidUrl"
                                class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                            >
                                Extract Audio
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Audio Format Selection -->
                <div x-show="showOptions" class="mb-8">
                    <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">Audio Options</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Format Selection -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-3">Output Format</label>
                            <div class="space-y-2">
                                @foreach($pageData['supportedFormats'] as $format)
                                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="radio" name="format" value="{{ strtolower($format) }}" x-model="selectedFormat" class="mr-3">
                                        <div class="flex-1">
                                            <div class="font-medium theme-heading dark:theme-heading">{{ $format }}</div>
                                            <div class="text-sm theme-base dark:theme-base">
                                                @switch($format)
                                                    @case('MP3')
                                                        Most compatible, good compression
                                                        @break
                                                    @case('WAV')
                                                        Uncompressed, best quality
                                                        @break
                                                    @case('M4A')
                                                        Apple format, good quality
                                                        @break
                                                    @case('AAC')
                                                        High quality, efficient
                                                        @break
                                                    @case('OGG')
                                                        Open source format
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quality Settings -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-3">Audio Quality</label>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Bitrate</label>
                                    <select x-model="audioBitrate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                        <option value="128">128 kbps (Standard)</option>
                                        <option value="192">192 kbps (Good)</option>
                                        <option value="256">256 kbps (High)</option>
                                        <option value="320">320 kbps (Maximum)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Sample Rate</label>
                                    <select x-model="sampleRate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                        <option value="44100">44.1 kHz (CD Quality)</option>
                                        <option value="48000">48 kHz (Professional)</option>
                                    </select>
                                </div>
                            </div>

                            <button 
                                @click="startAudioExtraction()"
                                :disabled="extracting"
                                class="w-full mt-6 theme-purple-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50"
                            >
                                <span x-show="!extracting">Download Audio</span>
                                <span x-show="extracting">Extracting Audio...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Extraction Progress -->
                <div x-show="extracting" class="mb-8">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900 dark:to-pink-900 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm theme-heading dark:theme-heading font-medium">Extracting audio track...</span>
                            <span class="text-sm theme-base dark:theme-base" x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-white dark:bg-gray-700 rounded-full h-2 mb-2">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                        </div>
                        <div class="text-xs theme-base dark:theme-base">
                            Processing: <span x-text="selectedFormat.toUpperCase()"></span> @ <span x-text="audioBitrate"></span> kbps
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Benefits -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">High Quality Audio</h3>
            <p class="theme-base dark:theme-base">Extract audio in up to 320 kbps quality for crystal clear sound.</p>
        </div>
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.57 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.57 4 8 4s8-1.79 8-4M4 7c0-2.21 3.57-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Multiple Formats</h3>
            <p class="theme-base dark:theme-base">Support for MP3, WAV, M4A, AAC, and OGG audio formats.</p>
        </div>
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Fast Extraction</h3>
            <p class="theme-base dark:theme-base">Quick audio extraction process with optimized conversion algorithms.</p>
        </div>
    </div>
</div>

<script>
function audioDownloader() {
    return {
        videoUrl: '',
        selectedFormat: 'mp3',
        audioBitrate: '192',
        sampleRate: '44100',
        isValidUrl: false,
        showOptions: false,
        extracting: false,
        progress: 0,

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
        },

        analyzeVideo() {
            if (!this.isValidUrl) return;
            this.showOptions = true;
        },

        startAudioExtraction() {
            this.extracting = true;
            this.progress = 0;

            const interval = setInterval(() => {
                this.progress += Math.random() * 12;
                if (this.progress >= 100) {
                    this.progress = 100;
                    this.extracting = false;
                    clearInterval(interval);
                    alert(`Audio extracted successfully as ${this.selectedFormat.toUpperCase()}!`);
                }
            }, 300);
        }
    }
}
</script>
@endsection
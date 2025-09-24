@extends('layouts.app')

@section('title', '1080p Video Downloader - Full HD Video Downloads')
@section('description', 'Download videos in crisp Full HD 1080p resolution. Fast, high-quality video downloading from popular platforms.')

@section('content')
<div class="container mx-auto px-5 py-12">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            {{ $pageData['title'] }}
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-2">
            {{ $pageData['subtitle'] }}
        </p>
        <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
            {{ $pageData['description'] }}
        </p>
    </div>

    <!-- 1080p Download Interface -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
            <!-- HD Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-8 text-white text-center">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <div class="bg-white/20 rounded-full p-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold">1080p</div>
                    <div class="bg-white/20 rounded-full px-4 py-2 text-sm font-semibold">FULL HD</div>
                </div>
                <h2 class="text-2xl font-bold mb-2">Full HD Video Downloads</h2>
                <p class="text-lg opacity-90">Crystal clear quality for every screen</p>
            </div>

            <div class="p-8" x-data="hdDownloader()">
                <!-- URL Input -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-4">
                        Video URL
                    </label>
                    <div class="url-input p-1">
                        <div class="flex">
                            <input 
                                x-model="videoUrl"
                                type="url" 
                                placeholder="Paste video URL for 1080p download..."
                                class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                @input="validateUrl()"
                            >
                            <button 
                                @click="analyzeVideo()"
                                :disabled="!isValidUrl || loading"
                                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-8 py-4 font-semibold transition-all duration-300 disabled:cursor-not-allowed rounded-r-lg"
                            >
                                <span x-show="!loading">Analyze</span>
                                <span x-show="loading">Loading...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Quality Preview -->
                <div x-show="showOptions" class="mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Video Info -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Video Information</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Resolution:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">1920×1080</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Quality:</span>
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Full HD</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Bitrate:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedBitrate + ' Mbps'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Frame Rate:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="selectedFrameRate + ' fps'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Download Options -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Download Options</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format</label>
                                        <select x-model="selectedFormat" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                                            <option value="mp4">MP4 (Recommended)</option>
                                            <option value="webm">WEBM (Web Optimized)</option>
                                            <option value="avi">AVI (Compatible)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bitrate</label>
                                        <select x-model="selectedBitrate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                                            <option value="4">4 Mbps (Standard)</option>
                                            <option value="6">6 Mbps (High)</option>
                                            <option value="8">8 Mbps (Premium)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frame Rate</label>
                                        <select x-model="selectedFrameRate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                                            <option value="30">30 fps (Standard)</option>
                                            <option value="60">60 fps (Smooth)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button 
                            @click="startHDDownload()"
                            :disabled="downloading"
                            class="w-full mt-6 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 disabled:from-gray-400 disabled:to-gray-500 text-white py-4 rounded-lg text-lg font-semibold transition-all duration-300 disabled:cursor-not-allowed"
                        >
                            <span x-show="!downloading">Download in 1080p HD</span>
                            <span x-show="downloading">Preparing HD Download...</span>
                        </button>
                    </div>
                </div>

                <!-- Download Progress -->
                <div x-show="downloading" class="mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-900 dark:text-white font-medium">Processing Full HD video...</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-white dark:bg-slate-700 rounded-full h-3 mb-3">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500" :style="`width: ${progress}%`"></div>
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            <div>Quality: 1080p • <span x-text="selectedBitrate"></span>Mbps • <span x-text="selectedFrameRate"></span>fps</div>
                            <div>Format: <span x-text="selectedFormat.toUpperCase()"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 1080p Features -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        @foreach($pageData['features'] as $feature)
            <div class="text-center p-8 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg hover:shadow-xl dark:hover:shadow-slate-700/50 transition-all duration-300 hover:scale-105">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($feature)
                            @case('Full HD quality')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                @break
                            @case('Fast downloading')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                @break
                            @case('Multiple format support')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                @break
                        @endswitch
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $feature }}</h3>
                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                    @switch($feature)
                        @case('Full HD quality')
                            Experience perfect 1920×1080 resolution with crisp detail and vibrant colors for any screen size.
                            @break
                        @case('Fast downloading')
                            Optimized servers and compression algorithms ensure the fastest possible 1080p download speeds.
                            @break
                        @case('Multiple format support')
                            Choose from MP4, WEBM, and AVI formats to ensure compatibility with all your devices and players.
                            @break
                    @endswitch
                </p>
            </div>
        @endforeach
    </div>

    <!-- Quality Comparison -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-8">Why Choose 1080p?</h2>
        <div class="grid md:grid-cols-3 gap-6 text-center">
            <div class="p-6 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="text-3xl font-bold text-gray-400 dark:text-gray-500 mb-2">720p</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">1280×720</div>
                <div class="text-sm text-gray-500 dark:text-gray-500 mt-2">Standard HD</div>
            </div>
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg transform scale-105">
                <div class="text-3xl font-bold mb-2">1080p</div>
                <div class="text-sm opacity-90">1920×1080</div>
                <div class="text-sm opacity-90 mt-2">Full HD ⭐</div>
            </div>
            <div class="p-6 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="text-3xl font-bold text-gray-600 dark:text-gray-400 mb-2">4K</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">3840×2160</div>
                <div class="text-sm text-gray-500 dark:text-gray-500 mt-2">Ultra HD</div>
            </div>
        </div>
        <div class="mt-6 text-center">
            <p class="text-gray-600 dark:text-gray-400">1080p offers the perfect balance of quality and file size - ideal for most devices and bandwidth requirements.</p>
        </div>
    </div>
</div>

<script>
function hdDownloader() {
    return {
        videoUrl: '',
        selectedFormat: 'mp4',
        selectedBitrate: '6',
        selectedFrameRate: '30',
        isValidUrl: false,
        showOptions: false,
        loading: false,
        downloading: false,
        progress: 0,

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
        },

        async analyzeVideo() {
            if (!this.isValidUrl) return;
            
            this.loading = true;
            
            // Simulate analysis
            setTimeout(() => {
                this.showOptions = true;
                this.loading = false;
            }, 1500);
        },

        async startHDDownload() {
            this.downloading = true;
            this.progress = 0;

            const interval = setInterval(() => {
                this.progress += Math.random() * 8;
                if (this.progress >= 100) {
                    this.progress = 100;
                    this.downloading = false;
                    clearInterval(interval);
                    alert(`1080p HD video download completed!\nFormat: ${this.selectedFormat.toUpperCase()}\nQuality: ${this.selectedBitrate}Mbps @ ${this.selectedFrameRate}fps`);
                }
            }, 400);
        }
    }
}
</script>
@endsection
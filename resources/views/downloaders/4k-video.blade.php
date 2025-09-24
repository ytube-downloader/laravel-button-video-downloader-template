@extends('layouts.app')

@section('title', '4K Video Downloader - Ultra HD Video Downloads')
@section('description', 'Download videos in stunning 4K ultra-high definition quality. Fast, secure 4K video downloading from popular platforms.')

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

    <!-- Download Interface -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <div x-data="fourKDownloader()">
                <!-- URL Input -->
                <div class="mb-8">
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-4">
                        Video URL
                    </label>
                    <div class="url-input p-1">
                        <div class="flex">
                            <input 
                                x-model="videoUrl"
                                type="url" 
                                placeholder="Paste video URL here..."
                                class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none theme-heading dark:theme-heading"
                                @input="validateUrl()"
                            >
                            <button 
                                @click="fetchVideoInfo()"
                                :disabled="!isValidUrl"
                                class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                            >
                                Analyze
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Info Display -->
                <div x-show="videoInfo" class="mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <img :src="videoInfo?.thumbnail" class="w-full h-32 object-cover rounded" alt="Video thumbnail">
                            </div>
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-2" x-text="videoInfo?.title"></h3>
                                <p class="text-sm theme-base dark:theme-base mb-4">
                                    Duration: <span x-text="videoInfo?.duration"></span>
                                </p>
                                
                                <!-- Quality Selection -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Quality</label>
                                        <select x-model="selectedQuality" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                            @foreach($pageData['supportedQualities'] as $quality)
                                                <option value="{{ strtolower(explode(' ', $quality)[0]) }}">{{ $quality }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Format</label>
                                        <select x-model="selectedFormat" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                            <option value="mp4">MP4 (Recommended)</option>
                                            <option value="webm">WEBM</option>
                                            <option value="mkv">MKV</option>
                                        </select>
                                    </div>
                                </div>

                                <button 
                                    @click="startDownload()"
                                    :disabled="downloading"
                                    class="w-full theme-purple-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50"
                                >
                                    <span x-show="!downloading">Download 4K Video</span>
                                    <span x-show="downloading">Preparing Download...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Display -->
                <div x-show="downloading" class="mb-8">
                    <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm theme-heading dark:theme-heading">Processing 4K video...</span>
                            <span class="text-sm theme-base dark:theme-base" x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="theme-purple-bg h-3 rounded-full transition-all duration-500" :style="`width: ${progress}%`"></div>
                        </div>
                        <p class="text-xs theme-base dark:theme-base mt-2">4K videos may take longer due to larger file sizes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4K Features -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Ultra HD Quality</h3>
            <p class="theme-base dark:theme-base">Experience crystal clear 4K resolution with exceptional detail and sharpness.</p>
        </div>
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Optimized Processing</h3>
            <p class="theme-base dark:theme-base">Advanced algorithms ensure the fastest possible 4K download speeds.</p>
        </div>
        <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Premium Experience</h3>
            <p class="theme-base dark:theme-base">Enjoy the ultimate viewing experience with cinema-quality downloads.</p>
        </div>
    </div>
</div>

<script>
function fourKDownloader() {
    return {
        videoUrl: '',
        videoInfo: null,
        selectedQuality: '4k',
        selectedFormat: 'mp4',
        isValidUrl: false,
        downloading: false,
        progress: 0,

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
        },

        async fetchVideoInfo() {
            if (!this.isValidUrl) return;

            try {
                const response = await fetch('/api/video-info?' + new URLSearchParams({
                    url: this.videoUrl
                }));
                
                const data = await response.json();
                if (data.success) {
                    this.videoInfo = data.video_info;
                }
            } catch (error) {
                alert('Error loading video information.');
            }
        },

        async startDownload() {
            this.downloading = true;
            this.progress = 0;

            const interval = setInterval(() => {
                this.progress += Math.random() * 5; // Slower for 4K
                if (this.progress >= 100) {
                    this.progress = 100;
                    this.downloading = false;
                    clearInterval(interval);
                    alert('4K video download completed!');
                }
            }, 800);
        }
    }
}
</script>
@endsection

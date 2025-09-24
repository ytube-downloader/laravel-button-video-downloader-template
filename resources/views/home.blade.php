@extends('layouts.app')

@section('title', 'Video Downloader - Download Videos Online')
@section('description', 'Fast and secure online video downloader. Download videos from popular platforms in various qualities and formats.')

@section('content')
<div class="px-5 lg:px-0">
    <!-- Hero Section -->
    <section class="container mx-auto py-20 text-center">
        <h1 class="text-5xl lg:text-7xl font-bold theme-heading dark:theme-heading mb-6">
            {{ $heroData['title'] }}
        </h1>
        <p class="text-xl lg:text-2xl theme-base dark:theme-base mb-8 max-w-3xl mx-auto">
            {{ $heroData['subtitle'] }}
        </p>
        <p class="text-lg theme-base dark:theme-base mb-12 max-w-2xl mx-auto">
            {{ $heroData['description'] }}
        </p>
        
        <!-- Video URL Input -->
        <div class="max-w-4xl mx-auto mb-8" x-data="videoDownloader()">
            <div class="url-input p-1">
                <div class="flex">
                    <input 
                        x-model="videoUrl"
                        type="url" 
                        placeholder="Paste video URL here (e.g., https://example.com/watch?v=...)"
                        class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none theme-heading dark:theme-heading"
                        @input="validateUrl()"
                    >
                    <button 
                        @click="startDownload()"
                        :disabled="!isValidUrl"
                        class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                    >
                        Download
                    </button>
                </div>
            </div>
            
            <!-- Video Preview -->
            <div x-show="videoInfo" class="mt-8 max-w-2xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <div class="flex items-start space-x-4">
                        <img x-bind:src="videoInfo?.thumbnail" class="w-32 h-24 object-cover rounded" alt="Video thumbnail">
                        <div class="flex-1 text-left">
                            <h3 class="font-semibold theme-heading dark:theme-heading mb-2" x-text="videoInfo?.title"></h3>
                            <p class="text-sm theme-base dark:theme-base mb-4">Duration: <span x-text="videoInfo?.duration"></span></p>
                            
                            <!-- Quality & Format Selection -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Quality</label>
                                    <select x-model="selectedQuality" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                                        <template x-for="quality in videoInfo?.available_qualities" :key="quality">
                                            <option :value="quality.toLowerCase()" x-text="quality"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Format</label>
                                    <select x-model="selectedFormat" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                                        <template x-for="format in videoInfo?.available_formats" :key="format">
                                            <option :value="format.toLowerCase()" x-text="format"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            
                            <button 
                                @click="downloadVideo()"
                                class="w-full mt-4 theme-purple-bg text-white py-2 rounded font-medium hover:opacity-90 transition-opacity"
                            >
                                Download Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div x-show="downloading" class="mt-8 max-w-2xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm theme-heading dark:theme-heading">Downloading...</span>
                        <span class="text-sm theme-base dark:theme-base" x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="theme-purple-bg h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Buttons -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
            <a href="{{ route('downloader.4k-video') }}" class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="text-2xl mb-2">🎬</div>
                <div class="text-sm font-medium theme-heading dark:theme-heading">4K Videos</div>
            </a>
            <a href="{{ route('downloader.video-mp3') }}" class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="text-2xl mb-2">🎵</div>
                <div class="text-sm font-medium theme-heading dark:theme-heading">Audio Only</div>
            </a>
            <a href="{{ route('downloader.playlist') }}" class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="text-2xl mb-2">📋</div>
                <div class="text-sm font-medium theme-heading dark:theme-heading">Playlists</div>
            </a>
            <a href="{{ route('downloader.1080p') }}" class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="text-2xl mb-2">📺</div>
                <div class="text-sm font-medium theme-heading dark:theme-heading">HD Videos</div>
            </a>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="container mx-auto py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold theme-heading dark:theme-heading mb-4">Trusted by Millions</h2>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-8 opacity-60">
            @foreach($partners as $partner)
                <div class="text-2xl font-semibold text-gray-500">{{ $partner }}</div>
            @endforeach
        </div>
    </section>

    <!-- Features Section -->
    <section class="container mx-auto py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold theme-heading dark:theme-heading mb-4">Powerful Download Features</h2>
            <p class="text-xl theme-base dark:theme-base max-w-2xl mx-auto">
                Everything you need for professional video downloading
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($features as $feature)
                <div class="text-center p-6">
                    <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @switch($feature['icon'])
                                @case('video')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    @break
                                @case('audio')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    @break
                                @case('playlist')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    @break
                                @case('fast')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    @break
                            @endswitch
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">
                        {{ $feature['title'] }}
                    </h3>
                    <p class="theme-base dark:theme-base">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="container mx-auto py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold theme-heading dark:theme-heading mb-4">Frequently Asked Questions</h2>
        </div>

        <div class="max-w-3xl mx-auto space-y-4" x-data="{ openFaq: null }">
            @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
                    <button 
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800"
                        @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                    >
                        <span class="font-semibold theme-heading dark:theme-heading">{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'transform rotate-180': openFaq === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-collapse class="px-6 pb-4">
                        <p class="theme-base dark:theme-base">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<script>
function videoDownloader() {
    return {
        videoUrl: '',
        videoInfo: null,
        selectedQuality: '1080p',
        selectedFormat: 'mp4',
        isValidUrl: false,
        downloading: false,
        progress: 0,

        validateUrl() {
            // Basic URL validation - in production, you'd validate against supported platforms
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
        },

        async startDownload() {
            if (!this.isValidUrl) return;

            try {
                const response = await fetch('/api/video-info?' + new URLSearchParams({
                    url: this.videoUrl
                }));
                
                const data = await response.json();
                if (data.success) {
                    this.videoInfo = data.video_info;
                    this.selectedQuality = data.video_info.available_qualities[0]?.toLowerCase() || '1080p';
                    this.selectedFormat = data.video_info.available_formats[0]?.toLowerCase() || 'mp4';
                }
            } catch (error) {
                console.error('Error fetching video info:', error);
                alert('Error loading video information. Please check the URL and try again.');
            }
        },

        async downloadVideo() {
            this.downloading = true;
            this.progress = 0;

            try {
                const response = await fetch('/api/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        url: this.videoUrl,
                        quality: this.selectedQuality,
                        format: this.selectedFormat
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Simulate download progress
                    const interval = setInterval(() => {
                        this.progress += Math.random() * 10;
                        if (this.progress >= 100) {
                            this.progress = 100;
                            this.downloading = false;
                            clearInterval(interval);
                            
                            // In a real app, you'd provide the download link
                            setTimeout(() => {
                                alert('Download completed! The file has been saved to your downloads folder.');
                                this.videoInfo = null;
                                this.videoUrl = '';
                                this.progress = 0;
                            }, 500);
                        }
                    }, 200);
                }
            } catch (error) {
                this.downloading = false;
                console.error('Download error:', error);
                alert('Download failed. Please try again.');
            }
        }
    }
}
</script>
@endsection
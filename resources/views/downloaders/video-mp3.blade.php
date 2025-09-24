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
                                :disabled="loading || extracting"
                            >
                            <button 
                                @click="analyzeVideo()"
                                :disabled="!isValidUrl || loading"
                                class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                            >
                                <span x-show="!loading">Extract Audio</span>
                                <span x-show="loading">Analyzing...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Error Display -->
                <div x-show="errorMessage" class="mb-8 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-red-700 dark:text-red-300" x-text="errorMessage"></span>
                    </div>
                </div>

                <!-- Video Info Display -->
                <div x-show="audioInfo" class="mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <img :src="audioInfo?.thumbnail" class="w-full h-32 object-cover rounded border border-gray-200 dark:border-gray-600" alt="Video thumbnail">
                            </div>
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-2" x-text="audioInfo?.title"></h3>
                                <p class="text-sm theme-base dark:theme-base mb-2">
                                    Duration: <span x-text="audioInfo?.duration"></span>
                                </p>
                                <p class="text-sm theme-base dark:theme-base mb-4">
                                    <span x-text="audioInfo?.uploader"></span> • <span x-text="audioInfo?.views"></span>
                                </p>
                            </div>
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
                                                    @case('FLAC')
                                                        Lossless compression
                                                        @break
                                                    @case('OGG')
                                                        Open source format
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                        <div class="text-xs theme-base dark:theme-base">
                                            <span x-show="audioInfo?.estimated_sizes && selectedFormat === '{{ strtolower($format) }}'" 
                                                  x-text="getEstimatedSize('{{ strtolower($format) }}')"></span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quality Settings -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-3">Audio Quality</label>
                            <div class="space-y-3">
                                <div x-show="selectedFormat === 'mp3' || selectedFormat === 'm4a' || selectedFormat === 'aac'">
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Bitrate</label>
                                    <select x-model="audioBitrate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                        @foreach($pageData['audio_qualities'] as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs theme-base dark:theme-base mb-1">Sample Rate</label>
                                    <select x-model="sampleRate" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                        <option value="44100">44.1 kHz (CD Quality)</option>
                                        <option value="48000">48 kHz (Professional)</option>
                                        <option value="96000" x-show="selectedFormat === 'wav' || selectedFormat === 'flac'">96 kHz (Hi-Res)</option>
                                    </select>
                                </div>

                                <div class="space-y-2 mt-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" x-model="normalizeAudio" class="mr-2">
                                        <span class="text-sm theme-heading dark:theme-heading">Normalize audio levels</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" x-model="removeNoise" class="mr-2">
                                        <span class="text-sm theme-heading dark:theme-heading">Apply noise reduction</span>
                                    </label>
                                </div>
                            </div>

                            <button 
                                @click="startAudioExtraction()"
                                :disabled="extracting || !audioInfo"
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
                            <div>Format: <span x-text="selectedFormat.toUpperCase()"></span></div>
                            <div x-show="selectedFormat !== 'flac' && selectedFormat !== 'wav'">Quality: <span x-text="audioBitrate"></span> kbps</div>
                            <div x-show="currentDownloadId">Download ID: <span x-text="currentDownloadId"></span></div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div x-show="downloadCompleted" class="mb-8">
                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-lg font-medium text-green-800 dark:text-green-200">Audio extraction completed!</span>
                        </div>
                        <div class="text-sm text-green-700 dark:text-green-300 mb-4">
                            <div>Format: <span x-text="selectedFormat.toUpperCase()"></span></div>
                            <div x-show="completedFileSize">File size: <span x-text="completedFileSize"></span></div>
                        </div>
                        <a :href="downloadUrl" 
                           x-show="downloadUrl"
                           class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                           download>
                            Download Audio File
                        </a>
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
            <p class="theme-base dark:theme-base">Support for MP3, WAV, M4A, AAC, FLAC, and OGG audio formats.</p>
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
        // Form data
        videoUrl: '',
        selectedFormat: 'mp3',
        audioBitrate: '192',
        sampleRate: '44100',
        normalizeAudio: false,
        removeNoise: false,

        // UI state
        isValidUrl: false,
        loading: false,
        showOptions: false,
        extracting: false,
        downloadCompleted: false,
        
        // Progress and results
        progress: 0,
        audioInfo: null,
        currentDownloadId: null,
        downloadUrl: null,
        completedFileSize: null,
        errorMessage: null,

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
            if (this.isValidUrl) {
                this.errorMessage = null;
            }
        },

        async analyzeVideo() {
            if (!this.isValidUrl) return;

            this.loading = true;
            this.errorMessage = null;
            this.showOptions = false;
            this.audioInfo = null;

            try {
                const response = await fetch('/api/audio-info?' + new URLSearchParams({
                    url: this.videoUrl
                }), {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.audioInfo = data.audio_info;
                    this.showOptions = true;
                } else {
                    this.errorMessage = data.error || 'Failed to analyze video';
                }
            } catch (error) {
                console.error('Error analyzing video:', error);
                this.errorMessage = 'Network error. Please check your connection and try again.';
            } finally {
                this.loading = false;
            }
        },

        async startAudioExtraction() {
            if (!this.audioInfo) return;

            this.extracting = true;
            this.downloadCompleted = false;
            this.progress = 0;
            this.errorMessage = null;
            this.currentDownloadId = null;
            this.downloadUrl = null;

            try {
                // Choose the appropriate endpoint
                const endpoint = this.selectedFormat === 'mp3' ? '/api/download-mp3' : '/api/download-audio';
                
                const requestData = {
                    url: this.videoUrl,
                    format: this.selectedFormat,
                    sample_rate: parseInt(this.sampleRate),
                    normalize: this.normalizeAudio,
                    remove_noise: this.removeNoise
                };

                // Add bitrate for compressed formats
                if (['mp3', 'm4a', 'aac'].includes(this.selectedFormat)) {
                    requestData.bitrate = parseInt(this.audioBitrate);
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestData)
                });

                const data = await response.json();

                if (data.success) {
                    this.currentDownloadId = data.download_id;
                    this.startProgressTracking();
                } else {
                    throw new Error(data.error || 'Audio extraction failed');
                }

            } catch (error) {
                console.error('Error starting extraction:', error);
                this.errorMessage = error.message || 'Failed to start audio extraction';
                this.extracting = false;
            }
        },

        async startProgressTracking() {
            if (!this.currentDownloadId) return;

            const progressInterval = setInterval(async () => {
                try {
                    const response = await fetch('/api/audio-download-status?' + new URLSearchParams({
                        download_id: this.currentDownloadId
                    }), {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.progress = Math.min(data.progress || 0, 100);

                        if (data.status === 'completed') {
                            this.progress = 100;
                            this.extracting = false;
                            this.downloadCompleted = true;
                            this.downloadUrl = data.download_url;
                            this.completedFileSize = data.file_size;
                            clearInterval(progressInterval);
                        } else if (data.status === 'failed') {
                            throw new Error(data.error || 'Download failed');
                        }
                    } else {
                        throw new Error(data.error || 'Failed to get download status');
                    }
                } catch (error) {
                    console.error('Error checking progress:', error);
                    this.errorMessage = error.message || 'Failed to track progress';
                    this.extracting = false;
                    clearInterval(progressInterval);
                }
            }, 2000); // Check every 2 seconds

            // Fallback timeout after 10 minutes
            setTimeout(() => {
                if (this.extracting) {
                    clearInterval(progressInterval);
                    this.errorMessage = 'Download timeout. Please try again with a shorter video.';
                    this.extracting = false;
                }
            }, 600000);
        },

        getEstimatedSize(format) {
            if (!this.audioInfo?.estimated_sizes) return '';
            
            const sizeKey = format === 'mp3' ? `mp3_${this.audioBitrate}` : format;
            const size = this.audioInfo.estimated_sizes[sizeKey];
            
            return size?.formatted || '';
        },

        resetForm() {
            this.videoUrl = '';
            this.audioInfo = null;
            this.showOptions = false;
            this.extracting = false;
            this.downloadCompleted = false;
            this.progress = 0;
            this.currentDownloadId = null;
            this.downloadUrl = null;
            this.errorMessage = null;
            this.isValidUrl = false;
        }
    }
}
</script>
@endsection
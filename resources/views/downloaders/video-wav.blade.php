@extends('layouts.app')

@section('title', 'Video to WAV Downloader - High Quality Audio Extraction')
@section('description', 'Extract and download video audio as high-quality WAV files. Lossless audio extraction from popular platforms.')

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

    <!-- WAV Conversion Interface -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Professional Audio Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white text-center">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-3xl font-bold">WAV</div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-2">Professional Audio Extraction</h2>
                <p class="text-lg opacity-90">Uncompressed WAV format for studio-quality audio</p>
            </div>

            <div class="p-8" x-data="wavDownloader()">
                <!-- URL Input -->
                <div class="mb-8">
                    <div class="url-input p-1">
                        <div class="flex">
                            <input 
                                x-model="videoUrl"
                                type="url" 
                                placeholder="Paste video URL for WAV audio extraction..."
                                class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none theme-heading dark:theme-heading"
                                @input="validateUrl()"
                            >
                            <button 
                                @click="analyzeForWav()"
                                :disabled="!isValidUrl"
                                class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                            >
                                Analyze Audio
                            </button>
                        </div>
                    </div>
                </div>

                <!-- WAV Quality Settings -->
                <div x-show="showWavOptions" class="mb-8">
                    <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-6">WAV Audio Settings</h3>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Quality Settings -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium theme-heading dark:theme-heading mb-3">Sample Rate</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="radio" x-model="sampleRate" value="44100" class="mr-3">
                                        <div>
                                            <div class="font-medium theme-heading dark:theme-heading">44.1 kHz</div>
                                            <div class="text-sm theme-base dark:theme-base">CD Quality Standard</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="radio" x-model="sampleRate" value="48000" class="mr-3">
                                        <div>
                                            <div class="font-medium theme-heading dark:theme-heading">48 kHz</div>
                                            <div class="text-sm theme-base dark:theme-base">Professional Standard</div>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="radio" x-model="sampleRate" value="96000" class="mr-3">
                                        <div>
                                            <div class="font-medium theme-heading dark:theme-heading">96 kHz</div>
                                            <div class="text-sm theme-base dark:theme-base">High Resolution Audio</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium theme-heading dark:theme-heading mb-3">Bit Depth</label>
                                <select x-model="bitDepth" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                    <option value="16">16-bit (Standard)</option>
                                    <option value="24">24-bit (Professional)</option>
                                    <option value="32">32-bit (Maximum Quality)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Audio Preview & Info -->
                        <div>
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-6 mb-6">
                                <h4 class="font-semibold theme-heading dark:theme-heading mb-3">Output Specifications</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="theme-base dark:theme-base">Sample Rate:</span>
                                        <span class="theme-heading dark:theme-heading" x-text="(sampleRate / 1000) + ' kHz'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="theme-base dark:theme-base">Bit Depth:</span>
                                        <span class="theme-heading dark:theme-heading" x-text="bitDepth + '-bit'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="theme-base dark:theme-base">Format:</span>
                                        <span class="theme-heading dark:theme-heading">Uncompressed WAV</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="theme-base dark:theme-base">Quality:</span>
                                        <span class="theme-heading dark:theme-heading">Lossless</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" x-model="normalizeAudio" class="mr-2">
                                    <label class="text-sm theme-heading dark:theme-heading">Normalize audio levels</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" x-model="removeNoise" class="mr-2">
                                    <label class="text-sm theme-heading dark:theme-heading">Apply noise reduction</label>
                                </div>
                            </div>

                            <button 
                                @click="startWavExtraction()"
                                :disabled="extracting"
                                class="w-full mt-6 bg-gradient-to-r from-blue-500 to-purple-500 text-white py-4 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50"
                            >
                                <span x-show="!extracting">Extract as WAV</span>
                                <span x-show="extracting">Extracting WAV Audio...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Extraction Progress -->
                <div x-show="extracting" class="mb-8">
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900 dark:to-purple-900 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm theme-heading dark:theme-heading font-medium">Processing uncompressed audio...</span>
                            <span class="text-sm theme-base dark:theme-base" x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-white dark:bg-gray-700 rounded-full h-3 mb-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" :style="`width: ${progress}%`"></div>
                        </div>
                        <div class="text-xs theme-base dark:theme-base">
                            <div>Quality: <span x-text="sampleRate / 1000"></span>kHz, <span x-text="bitDepth"></span>-bit WAV</div>
                            <div x-show="normalizeAudio">• Normalizing audio levels</div>
                            <div x-show="removeNoise">• Applying noise reduction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WAV Benefits -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        @foreach($pageData['benefits'] as $benefit)
            <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($benefit)
                            @case('Lossless audio quality')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                @break
                            @case('Professional standard')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                @break
                            @case('Universal compatibility')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                                @break
                        @endswitch
                    </svg>
                </div>
                <h3 class="text-2xl font-bold theme-heading dark:theme-heading mb-4">{{ $benefit }}</h3>
                <p class="text-lg theme-base dark:theme-base leading-relaxed">
                    @switch($benefit)
                        @case('Lossless audio quality')
                            Perfect reproduction of the original audio with zero compression artifacts for pure sound.
                            @break
                        @case('Professional standard')
                            Industry-standard format used by audio professionals and studios worldwide.
                            @break
                        @case('Universal compatibility')
                            WAV files work seamlessly with all audio software, hardware, and devices.
                            @break
                    @endswitch
                </p>
            </div>
        @endforeach
    </div>
</div>

<script>
function wavDownloader() {
    return {
        videoUrl: '',
        sampleRate: '44100',
        bitDepth: '16',
        normalizeAudio: false,
        removeNoise: false,
        isValidUrl: false,
        showWavOptions: false,
        extracting: false,
        progress: 0,

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.videoUrl);
        },

        analyzeForWav() {
            if (!this.isValidUrl) return;
            this.showWavOptions = true;
        },

        startWavExtraction() {
            this.extracting = true;
            this.progress = 0;

            const interval = setInterval(() => {
                this.progress += Math.random() * 8; // Slower for uncompressed WAV
                if (this.progress >= 100) {
                    this.progress = 100;
                    this.extracting = false;
                    clearInterval(interval);
                    alert(`High-quality WAV audio extracted successfully!\nQuality: ${this.sampleRate / 1000}kHz, ${this.bitDepth}-bit`);
                }
            }, 600);
        }
    }
}
</script>
@endsection

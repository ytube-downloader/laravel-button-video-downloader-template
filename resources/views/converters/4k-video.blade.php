@extends('layouts.app')

@section('title', '4K Video Converter - Ultra HD Video Conversion')
@section('description', 'Convert videos to stunning 4K ultra-high definition quality. Fast, secure, and professional 4K video conversion online.')

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

    <!-- Conversion Tool -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <form class="space-y-6" x-data="conversionForm()">
                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">
                        Select Video File
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-purple-500 transition-colors">
                        <input type="file" class="hidden" x-ref="fileInput" @change="handleFileSelect" accept="video/*">
                        <button type="button" @click="$refs.fileInput.click()" class="w-full">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-lg theme-heading dark:theme-heading mb-2">Choose video file</p>
                            <p class="text-sm theme-base dark:theme-base">or drag and drop here</p>
                        </button>
                    </div>
                    <div x-show="selectedFile" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm theme-heading dark:theme-heading">
                            Selected: <span x-text="selectedFile?.name"></span>
                        </p>
                    </div>
                </div>

                <!-- Format Selection -->
                <div>
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">
                        Output Format
                    </label>
                    <select x-model="outputFormat" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        @foreach($pageData['supportedFormats'] as $format)
                            <option value="{{ strtolower($format) }}">{{ $format }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quality Settings -->
                <div>
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">
                        Quality Settings
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="quality" value="4k" x-model="quality" class="mr-2">
                            <span class="text-sm">4K (2160p)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quality" value="2k" x-model="quality" class="mr-2">
                            <span class="text-sm">2K (1440p)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quality" value="1080p" x-model="quality" class="mr-2">
                            <span class="text-sm">1080p</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quality" value="720p" x-model="quality" class="mr-2">
                            <span class="text-sm">720p</span>
                        </label>
                    </div>
                </div>

                <!-- Convert Button -->
                <button 
                    type="button" 
                    @click="startConversion()"
                    :disabled="!selectedFile"
                    class="w-full theme-purple-bg text-white py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Convert to 4K
                </button>

                <!-- Progress Bar -->
                <div x-show="converting" class="w-full bg-gray-200 rounded-full h-2">
                    <div class="theme-purple-bg h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        <div class="text-center p-6">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <div class="w-8 h-8 bg-white rounded"></div>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Ultra HD Quality</h3>
            <p class="theme-base dark:theme-base">Experience crystal clear 4K resolution with enhanced detail and clarity.</p>
        </div>
        <div class="text-center p-6">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <div class="w-8 h-8 bg-white rounded"></div>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Fast Processing</h3>
            <p class="theme-base dark:theme-base">Advanced algorithms ensure quick conversion without compromising quality.</p>
        </div>
        <div class="text-center p-6">
            <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                <div class="w-8 h-8 bg-white rounded"></div>
            </div>
            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">Multiple Formats</h3>
            <p class="theme-base dark:theme-base">Support for all major video formats including MP4, MOV, AVI, and more.</p>
        </div>
    </div>
</div>

<script>
function conversionForm() {
    return {
        selectedFile: null,
        outputFormat: 'mp4',
        quality: '4k',
        converting: false,
        progress: 0,

        handleFileSelect(event) {
            this.selectedFile = event.target.files[0];
        },

        async startConversion() {
            if (!this.selectedFile) return;
            
            this.converting = true;
            this.progress = 0;

            // Simulate conversion progress
            const interval = setInterval(() => {
                this.progress += Math.random() * 10;
                if (this.progress >= 100) {
                    this.progress = 100;
                    this.converting = false;
                    clearInterval(interval);
                    alert('Conversion completed! Download link sent to your browser.');
                }
            }, 500);
        }
    }
}
</script>
@endsection
@extends('layouts.app')

@section('title', '1080p Video Converter - Full HD Video Conversion')
@section('description', 'Convert videos to Full HD 1080p resolution. Professional quality video conversion with fast processing.')

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

    <!-- Quick Convert Section -->
    <div class="max-w-5xl mx-auto mb-16">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-8 text-white mb-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold mb-4">Quick 1080p Conversion</h2>
                <p class="text-lg opacity-90 mb-6">Upload your video and get Full HD quality in minutes</p>
                
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white bg-opacity-20 rounded-xl p-6 backdrop-blur">
                        <div class="flex items-center justify-center space-x-8 text-sm">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="font-bold">1</span>
                                </div>
                                <span>Upload</span>
                            </div>
                            <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="font-bold">2</span>
                                </div>
                                <span>Convert</span>
                            </div>
                            <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-white bg-opacity-30 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <span class="font-bold">3</span>
                                </div>
                                <span>Download</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversion Tool -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <div class="grid lg:grid-cols-2 gap-8" x-data="{ selectedFile: null, processing: false }">
                <!-- Left Column - Upload -->
                <div>
                    <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-6">Upload Video File</h3>
                    
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-purple-500 transition-colors">
                        <input type="file" class="hidden" x-ref="fileInput" accept="video/*" @change="selectedFile = $event.target.files[0]">
                        <button type="button" @click="$refs.fileInput.click()" class="w-full">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-lg theme-heading dark:theme-heading font-semibold mb-2">Choose video file</p>
                            <p class="theme-base dark:theme-base">or drag and drop here</p>
                        </button>
                    </div>

                    <!-- File Info -->
                    <div x-show="selectedFile" class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-semibold theme-heading dark:theme-heading mb-2">Selected File:</h4>
                        <p class="text-sm theme-base dark:theme-base" x-text="selectedFile?.name"></p>
                        <p class="text-xs theme-base dark:theme-base mt-1" x-text="selectedFile ? Math.round(selectedFile.size / 1024 / 1024) + ' MB' : ''"></p>
                    </div>
                </div>

                <!-- Right Column - Settings -->
                <div>
                    <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-6">Conversion Settings</h3>
                    
                    <div class="space-y-6">
                        <!-- Resolution -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Output Resolution</label>
                            <select class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                <option value="1080p" selected>1080p (1920×1080) - Full HD</option>
                                <option value="720p">720p (1280×720) - HD</option>
                                <option value="480p">480p (854×480) - SD</option>
                            </select>
                        </div>

                        <!-- Format -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Output Format</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="format" value="mp4" class="mr-3" checked>
                                    <span class="font-medium">MP4</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="format" value="avi" class="mr-3">
                                    <span class="font-medium">AVI</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="format" value="mov" class="mr-3">
                                    <span class="font-medium">MOV</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="format" value="mkv" class="mr-3">
                                    <span class="font-medium">MKV</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quality -->
                        <div>
                            <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Quality</label>
                            <input type="range" min="1" max="5" value="4" class="w-full">
                            <div class="flex justify-between text-xs theme-base dark:theme-base mt-1">
                                <span>Compress</span>
                                <span>Balanced</span>
                                <span>Best Quality</span>
                            </div>
                        </div>

                        <!-- Convert Button -->
                        <button 
                            type="button"
                            @click="processing = true"
                            :disabled="!selectedFile || processing"
                            class="w-full theme-purple-bg text-white py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span x-show="!processing">Convert to 1080p</span>
                            <span x-show="processing">Converting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid md:grid-cols-3 gap-8">
        @foreach($pageData['features'] as $feature)
            <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="w-20 h-20 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($feature)
                            @case('Full HD quality')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                @break
                            @case('Fast processing')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                @break
                            @case('Multiple format support')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                @break
                        @endswitch
                    </svg>
                </div>
                <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-4">{{ $feature }}</h3>
                <p class="theme-base dark:theme-base">
                    @switch($feature)
                        @case('Full HD quality')
                            Convert videos to crisp 1920×1080 resolution with excellent clarity and detail.
                            @break
                        @case('Fast processing')
                            Our optimized algorithms ensure quick conversion while maintaining video quality.
                            @break
                        @case('Multiple format support')
                            Support for MP4, AVI, MOV, MKV and many other popular video formats.
                            @break
                    @endswitch
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
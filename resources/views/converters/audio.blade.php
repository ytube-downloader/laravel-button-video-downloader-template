@extends('layouts.app')

@section('title', 'Audio Converter - Convert Audio Files Online')
@section('description', 'Convert audio files to MP3, WAV, FLAC and other formats. Fast, secure online audio conversion tool.')

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
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Input Section -->
                <div>
                    <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">Input Audio</h3>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                        <p class="theme-heading dark:theme-heading mb-2">Upload Audio File</p>
                        <p class="text-sm theme-base dark:theme-base">Supports all major audio formats</p>
                        <input type="file" class="hidden" accept="audio/*">
                        <button class="mt-4 theme-purple-bg text-white px-6 py-2 rounded-lg">Choose File</button>
                    </div>
                </div>

                <!-- Output Section -->
                <div>
                    <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">Output Format</h3>
                    <div class="space-y-4">
                        @foreach($pageData['supportedFormats'] as $format)
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="format" value="{{ strtolower($format) }}" class="mr-3">
                                <div>
                                    <div class="font-medium theme-heading dark:theme-heading">{{ $format }}</div>
                                    <div class="text-sm theme-base dark:theme-base">
                                        @switch($format)
                                            @case('MP3')
                                                Most popular, small file size
                                                @break
                                            @case('WAV')
                                                Lossless quality, larger files
                                                @break
                                            @case('FLAC')
                                                Lossless compression
                                                @break
                                            @case('AAC')
                                                Good quality, efficient compression
                                                @break
                                            @case('OGG')
                                                Open source, good compression
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    <button class="w-full mt-6 theme-purple-bg text-white py-3 rounded-lg font-semibold">
                        Convert Audio
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
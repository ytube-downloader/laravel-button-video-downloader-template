@extends('layouts.app')

@section('title', 'Audio to WAV Converter - High Quality Audio Conversion')
@section('description', 'Convert audio files to high-quality WAV format. Lossless audio conversion for professional use.')

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

    <!-- Conversion Interface -->
    <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center space-x-4 p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                    <svg class="w-8 h-8 theme-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                    <div class="text-left">
                        <p class="font-semibold theme-heading dark:theme-heading">Audio → WAV</p>
                        <p class="text-sm theme-base dark:theme-base">Lossless quality conversion</p>
                    </div>
                </div>
            </div>

            <form class="space-y-8">
                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-4">Upload Audio File</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-12 text-center hover:border-purple-500 transition-colors">
                        <input type="file" class="hidden" accept="audio/*">
                        <div class="space-y-4">
                            <div class="w-20 h-20 mx-auto theme-purple-bg rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl theme-heading dark:theme-heading font-semibold mb-2">Choose audio file</p>
                                <p class="theme-base dark:theme-base">Supports MP3, M4A, FLAC, AAC, OGG and more</p>
                            </div>
                            <button type="button" class="theme-purple-bg text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity">
                                Select File
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quality Settings -->
                <div>
                    <label class="block text-sm font-medium theme-heading dark:theme-heading mb-4">WAV Quality Settings</label>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Sample Rate -->
                        <div>
                            <label class="block text-sm theme-base dark:theme-base mb-2">Sample Rate</label>
                            <select class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                <option value="44100">44.1 kHz (CD Quality)</option>
                                <option value="48000">48 kHz (Professional)</option>
                                <option value="96000">96 kHz (High Resolution)</option>
                                <option value="192000">192 kHz (Ultra High Resolution)</option>
                            </select>
                        </div>

                        <!-- Bit Depth -->
                        <div>
                            <label class="block text-sm theme-base dark:theme-base mb-2">Bit Depth</label>
                            <select class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                <option value="16">16-bit (Standard)</option>
                                <option value="24">24-bit (Professional)</option>
                                <option value="32">32-bit (Maximum Quality)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Convert Button -->
                <div class="text-center">
                    <button type="button" class="theme-purple-bg text-white px-12 py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity">
                        Convert to WAV
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Benefits -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        @foreach($pageData['benefits'] as $benefit)
            <div class="text-center p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-4">{{ $benefit }}</h3>
                <p class="theme-base dark:theme-base">
                    @switch($benefit)
                        @case('Lossless quality')
                            Perfect reproduction of original audio with no compression artifacts
                            @break
                        @case('Universal compatibility')
                            WAV files work with all audio software and hardware devices
                            @break
                        @case('Professional standard')
                            Industry-standard format for professional audio production
                            @break
                    @endswitch
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Media Converter - Convert Files Online')
@section('description', 'Fast and secure online media converter. Convert videos and audio files to various formats quickly and easily.')

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
        
        <!-- CTA Button -->
        <div class="mb-16">
            <button class="theme-purple-bg text-white px-8 py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity">
                Start Converting
            </button>
        </div>

        <!-- File Upload Area -->
        <div class="max-w-2xl mx-auto">
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-12 hover:border-purple-500 transition-colors">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="file-upload" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium theme-heading dark:theme-heading">
                                Drop files here or click to upload
                            </span>
                            <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple>
                        </label>
                        <p class="mt-1 text-xs theme-base dark:theme-base">
                            Supports video and audio files up to 100MB
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="container mx-auto py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold theme-heading dark:theme-heading mb-4">Trusted by Industry Leaders</h2>
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
            <h2 class="text-4xl font-bold theme-heading dark:theme-heading mb-4">Powerful Features</h2>
            <p class="text-xl theme-base dark:theme-base max-w-2xl mx-auto">
                Everything you need for professional media conversion
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($features as $feature)
                <div class="text-center p-6">
                    <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <!-- SVG icons would go here -->
                        <div class="w-8 h-8 bg-white rounded"></div>
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
@endsection
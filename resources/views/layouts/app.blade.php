<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Media Converter - Convert Files Online')</title>
    <meta name="description" content="@yield('description', 'Fast and secure online media converter. Convert videos and audio files to various formats quickly and easily.')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS for theme colors -->
    <style>
        :root {
            --color-body: #f3f4f6;
            --color-header-bg: #FFFFFE;
            --color-purple-main: #6c5ce7;
            --color-heading-main: #2D3436;
            --color-dark-heading-main: #FFFFFF;
            --color-base-one: #4A5455;
            --color-dark-base-one: #b8b8b8;
            --color-dark-body: #121316;
            --color-dark-heading: #191a1d;
            --color-partner: #9ca3af;
        }

        .theme-purple { color: var(--color-purple-main); }
        .theme-purple-bg { background-color: var(--color-purple-main); }
        .theme-heading { color: var(--color-heading-main); }
        .theme-base { color: var(--color-base-one); }
        
        @media (prefers-color-scheme: dark) {
            .dark\:theme-heading { color: var(--color-dark-heading-main); }
            .dark\:theme-base { color: var(--color-dark-base-one); }
            .dark\:theme-body-bg { background-color: var(--color-dark-body); }
            .dark\:theme-heading-bg { background-color: var(--color-dark-heading); }
        }
    </style>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
    <!-- Header -->
    <header class="bg-white dark:theme-heading-bg shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-3xl font-bold theme-purple">
                    VDA
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('home') }}" class="hover:theme-purple transition-colors">Home</a>
                    <a href="{{ route('converter.4k-video') }}" class="hover:theme-purple transition-colors">4K Video</a>
                    <a href="{{ route('converter.audio') }}" class="hover:theme-purple transition-colors">Audio</a>
                    <a href="{{ route('converter.batch') }}" class="hover:theme-purple transition-colors">Batch</a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden" x-data="{ open: false }" @click="open = !open">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:theme-heading-bg px-5 pt-32 pb-8">
        <div class="container mx-auto">
            <div class="lg:flex lg:flex-wrap lg:items-start lg:gap-x-14">
                <!-- Logo -->
                <div class="pb-8 border-b-2 border-gray-200 dark:border-gray-800 mb-8 lg:border-none">
                    <a href="{{ route('home') }}" class="block max-w-max mx-auto theme-purple font-bold text-4xl">
                        VDA
                    </a>
                </div>

                <!-- Links -->
                <div class="text-center lg:text-left">
                    <h3 class="text-xl font-bold theme-heading dark:theme-heading mb-4">More Tools</h3>
                    <ul class="theme-base dark:theme-base lg:grid lg:grid-cols-2 lg:gap-x-10">
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('home') }}">Media File Converter</a>
                        </li>
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('converter.4k-video') }}">4K Video Converter</a>
                        </li>
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('converter.audio') }}">Audio Converter</a>
                        </li>
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('converter.batch') }}">Batch Converter</a>
                        </li>
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('converter.audio-wav') }}">Audio to WAV</a>
                        </li>
                        <li class="text-sm font-light mb-8">
                            <a href="{{ route('converter.1080p') }}">1080p Video Converter</a>
                        </li>
                    </ul>
                </div>

                <!-- Copyright -->
                <div class="lg:basis-full p-4">
                    <div class="border-t-2 pt-16 border-gray-200 dark:border-gray-800">
                        <p class="font-light text-sm theme-base dark:theme-base">
                            Copyright © {{ date('Y') }} All Rights Reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
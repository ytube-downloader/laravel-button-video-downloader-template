<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Video Downloader - Download Videos Online')</title>
    <meta name="description" content="@yield('description', 'Fast and secure online video downloader. Download videos from popular platforms in various qualities and formats.')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'purple-main': '#6c5ce7',
                        'heading-main': '#2D3436',
                        'dark-heading-main': '#FFFFFF',
                        'base-one': '#4A5455',
                        'dark-base-one': '#b8b8b8',
                        'dark-body': '#0f172a',
                        'dark-heading': '#1e293b',
                        'partner': '#9ca3af'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS for theme colors -->
    <style>
        .theme-purple { 
            color: #6c5ce7; 
        }
        .theme-purple-bg { 
            background-color: #6c5ce7; 
        }
        .theme-heading { 
            color: #2D3436; 
        }
        .theme-base { 
            color: #4A5455; 
        }
        
        .dark .theme-heading { 
            color: #FFFFFF; 
        }
        .dark .theme-base { 
            color: #b8b8b8; 
        }

        .url-input {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 2px;
        }
        
        .url-input input {
            background: white;
            border-radius: 10px;
        }

        .dark .url-input input {
            background: #334155;
            color: white;
        }

        .dark .url-input input::placeholder {
            color: #94a3b8;
        }

        /* Dark mode toggle functionality */
        .dark-mode-toggle {
            transition: all 0.3s ease;
        }
    </style>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Dark mode script -->
    <script>
        // Check for saved dark mode preference or default to system preference
        if (localStorage.getItem('darkMode') === 'true' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-900 dark:text-white transition-colors duration-300">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-sm shadow-sm sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-3xl font-bold text-purple-600 hover:text-purple-700 transition-colors">
                    VDA
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Home</a>
                    <a href="{{ route('downloader.4k-video') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">4K Video</a>
                    <a href="{{ route('downloader.video-mp3') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Video to MP3</a>
                    <a href="{{ route('downloader.playlist') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Playlist</a>
                    <a href="{{ route('downloader.video-wav') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">WAV</a>
                    <a href="{{ route('downloader.1080p') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">1080p</a>
                </div>

                <!-- Dark Mode Toggle & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button 
                        onclick="toggleDarkMode()" 
                        class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                        title="Toggle dark mode"
                    >
                        <!-- Sun icon (shown in dark mode) -->
                        <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" 
                            x-data="{ open: false }" 
                            @click="open = !open"
                            :aria-expanded="open">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden mt-4 pb-4 border-t border-gray-200 dark:border-gray-700" x-data="{ open: false }" x-show="open" x-collapse>
                <div class="flex flex-col space-y-3 pt-4">
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Home</a>
                    <a href="{{ route('downloader.4k-video') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">4K Video</a>
                    <a href="{{ route('downloader.video-mp3') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Video to MP3</a>
                    <a href="{{ route('downloader.playlist') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">Playlist</a>
                    <a href="{{ route('downloader.video-wav') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">WAV</a>
                    <a href="{{ route('downloader.1080p') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">1080p</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-gray-700 px-5 pt-16 pb-8">
        <div class="container mx-auto">
            <div class="lg:flex lg:flex-wrap lg:items-start lg:gap-x-14">
                <!-- Logo -->
                <div class="pb-8 border-b-2 border-gray-200 dark:border-gray-700 mb-8 lg:border-none">
                    <a href="{{ route('home') }}" class="block max-w-max mx-auto text-purple-600 font-bold text-4xl hover:text-purple-700 transition-colors">
                        VDA
                    </a>
                </div>

                <!-- Links -->
                <div class="text-center lg:text-left">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Download Tools</h3>
                    <ul class="text-gray-600 dark:text-gray-400 lg:grid lg:grid-cols-2 lg:gap-x-10">
                        <li class="mb-4">
                            <a href="{{ route('home') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Video Downloader</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('downloader.4k-video') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">4K Video Downloader</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('downloader.video-mp3') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Video to MP3</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('downloader.playlist') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Playlist Downloader</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('downloader.video-wav') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Video to WAV</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('downloader.1080p') }}" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">1080p Video Downloader</a>
                        </li>
                    </ul>
                </div>

                <!-- Additional Links -->
                <div class="text-center lg:text-left lg:ml-auto">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Support</h3>
                    <ul class="text-gray-600 dark:text-gray-400 space-y-3">
                        <li>
                            <a href="#" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Help Center</a>
                        </li>
                        <li>
                            <a href="#" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Terms of Service</a>
                        </li>
                        <li>
                            <a href="#" class="text-sm hover:text-purple-600 dark:hover:text-purple-400 transition-colors">Contact Us</a>
                        </li>
                    </ul>
                </div>

                <!-- Copyright -->
                <div class="lg:basis-full mt-12">
                    <div class="border-t-2 pt-8 border-gray-200 dark:border-gray-700 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Copyright © {{ date('Y') }} VDA. All Rights Reserved.
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                            Built with ❤️ using Laravel & Tailwind CSS
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Dark Mode Toggle Script -->
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }

        // Listen for system dark mode changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('darkMode')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    </script>
</body>
</html>
@extends('layouts.app')

@section('title', 'Playlist Downloader - Batch Download Videos')
@section('description', 'Download entire playlists, channels, or video collections with one click. Batch video downloading made simple.')

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

    <!-- Playlist Downloader Interface -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8" x-data="playlistDownloader()">
            <!-- URL Input -->
            <div class="mb-8">
                <label class="block text-lg font-semibold theme-heading dark:theme-heading mb-4">
                    Playlist or Channel URL
                </label>
                <div class="url-input p-1">
                    <div class="flex">
                        <input 
                            x-model="playlistUrl"
                            type="url" 
                            placeholder="Paste playlist, channel, or collection URL..."
                            class="flex-1 px-6 py-4 text-lg border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none theme-heading dark:theme-heading"
                            @input="validateUrl()"
                        >
                        <button 
                            @click="loadPlaylist()"
                            :disabled="!isValidUrl || loading"
                            class="theme-purple-bg text-white px-8 py-4 font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed rounded-r-lg"
                        >
                            <span x-show="!loading">Load Playlist</span>
                            <span x-show="loading">Loading...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Playlist Info -->
            <div x-show="playlistInfo" class="mb-8">
                <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-6">
                    <div class="flex items-start space-x-4">
                        <img :src="playlistInfo?.thumbnail" class="w-24 h-18 object-cover rounded" alt="Playlist thumbnail">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2" x-text="playlistInfo?.title"></h3>
                            <p class="theme-base dark:theme-base mb-2">
                                <span x-text="playlistInfo?.video_count"></span> videos • 
                                <span x-text="playlistInfo?.total_duration"></span>
                            </p>
                            <p class="text-sm theme-base dark:theme-base" x-text="playlistInfo?.description"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Options -->
            <div x-show="playlistInfo" class="mb-8">
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Selection Options -->
                    <div>
                        <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">Download Selection</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="radio" x-model="downloadOption" value="all" class="mr-3">
                                <div>
                                    <div class="font-medium theme-heading dark:theme-heading">All Videos</div>
                                    <div class="text-sm theme-base dark:theme-base">Download the entire playlist</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="radio" x-model="downloadOption" value="range" class="mr-3">
                                <div>
                                    <div class="font-medium theme-heading dark:theme-heading">Video Range</div>
                                    <div class="text-sm theme-base dark:theme-base">Select start and end positions</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="radio" x-model="downloadOption" value="custom" class="mr-3">
                                <div>
                                    <div class="font-medium theme-heading dark:theme-heading">Custom Selection</div>
                                    <div class="text-sm theme-base dark:theme-base">Choose specific videos</div>
                                </div>
                            </label>
                        </div>

                        <!-- Range Selection -->
                        <div x-show="downloadOption === 'range'" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm theme-base dark:theme-base mb-1">From video #</label>
                                    <input type="number" x-model="rangeStart" min="1" :max="playlistInfo?.video_count" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800">
                                </div>
                                <div>
                                    <label class="block text-sm theme-base dark:theme-base mb-1">To video #</label>
                                    <input type="number" x-model="rangeEnd" :min="rangeStart" :max="playlistInfo?.video_count" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Format & Quality -->
                    <div>
                        <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">Download Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Quality</label>
                                <select x-model="batchQuality" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                    <option value="1080p">1080p (Full HD)</option>
                                    <option value="720p">720p (HD)</option>
                                    <option value="480p">480p (SD)</option>
                                    <option value="audio">Audio Only (MP3)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium theme-heading dark:theme-heading mb-2">Format</label>
                                <select x-model="batchFormat" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                    <option value="mp4">MP4 (Video)</option>
                                    <option value="webm">WEBM (Video)</option>
                                    <option value="mp3">MP3 (Audio)</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" x-model="createPlaylist" class="mr-2">
                                <label class="text-sm theme-heading dark:theme-heading">Create playlist file (.m3u)</label>
                            </div>
                        </div>

                        <button 
                            @click="startBatchDownload()"
                            :disabled="downloading"
                            class="w-full mt-6 theme-purple-bg text-white py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50"
                        >
                            <span x-show="!downloading">Start Batch Download</span>
                            <span x-show="downloading">Downloading...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Download Progress -->
            <div x-show="downloading" class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-purple-200 dark:border-purple-600 p-6">
                    <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">
                        Batch Download Progress
                    </h3>
                    
                    <!-- Overall Progress -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm theme-heading dark:theme-heading">
                                Overall: <span x-text="completedVideos"></span> / <span x-text="totalVideos"></span> videos
                            </span>
                            <span class="text-sm theme-base dark:theme-base" x-text="Math.round((completedVideos / totalVideos) * 100) + '%'"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="theme-purple-bg h-3 rounded-full transition-all duration-500" :style="`width: ${(completedVideos / totalVideos) * 100}%`"></div>
                        </div>
                    </div>

                    <!-- Individual Video Progress -->
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <template x-for="(video, index) in downloadQueue" :key="index">
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold" 
                                     :class="video.status === 'completed' ? 'bg-green-100 text-green-600' : 
                                             video.status === 'downloading' ? 'bg-purple-100 text-purple-600' : 
                                             'bg-gray-100 text-gray-600'">
                                    <span x-show="video.status === 'completed'">✓</span>
                                    <span x-show="video.status === 'downloading'" x-text="video.progress + '%'"></span>
                                    <span x-show="video.status === 'pending'" x-text="index + 1"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium theme-heading dark:theme-heading truncate" x-text="video.title"></p>
                                    <p class="text-xs theme-base dark:theme-base" x-text="video.duration"></p>
                                </div>
                                <div class="text-xs theme-base dark:theme-base" x-text="video.status"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-4 gap-6 mt-16">
            @foreach($pageData['features'] as $feature)
                <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                    <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @switch($feature)
                                @case('Batch downloading')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    @break
                                @case('Queue management')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    @break
                                @case('Progress tracking')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    @break
                                @case('Format selection per video')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    @break
                            @endswitch
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-2">{{ $feature }}</h3>
                    <p class="text-sm theme-base dark:theme-base">Advanced playlist processing with comprehensive management tools.</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function playlistDownloader() {
    return {
        playlistUrl: '',
        playlistInfo: null,
        downloadOption: 'all',
        rangeStart: 1,
        rangeEnd: 10,
        batchQuality: '1080p',
        batchFormat: 'mp4',
        createPlaylist: true,
        isValidUrl: false,
        loading: false,
        downloading: false,
        completedVideos: 0,
        totalVideos: 0,
        downloadQueue: [],

        validateUrl() {
            const urlPattern = /^https?:\/\/.+/;
            this.isValidUrl = urlPattern.test(this.playlistUrl);
        },

        async loadPlaylist() {
            this.loading = true;
            
            // Simulate playlist loading
            setTimeout(() => {
                this.playlistInfo = {
                    title: 'Sample Playlist Collection',
                    video_count: 25,
                    total_duration: '2h 45m',
                    description: 'A curated collection of educational videos',
                    thumbnail: 'https://via.placeholder.com/120x90'
                };
                this.rangeEnd = this.playlistInfo.video_count;
                this.loading = false;
            }, 2000);
        },

        async startBatchDownload() {
            this.downloading = true;
            this.completedVideos = 0;
            
            // Determine how many videos to download
            if (this.downloadOption === 'all') {
                this.totalVideos = this.playlistInfo.video_count;
            } else if (this.downloadOption === 'range') {
                this.totalVideos = this.rangeEnd - this.rangeStart + 1;
            } else {
                this.totalVideos = 10; // Custom selection example
            }

            // Create download queue
            this.downloadQueue = Array.from({length: this.totalVideos}, (_, i) => ({
                title: `Video ${i + 1}: Sample Title`,
                duration: '3:45',
                status: 'pending',
                progress: 0
            }));

            // Simulate downloading videos one by one
            for (let i = 0; i < this.totalVideos; i++) {
                this.downloadQueue[i].status = 'downloading';
                
                // Simulate download progress for individual video
                const downloadInterval = setInterval(() => {
                    this.downloadQueue[i].progress += Math.random() * 20;
                    if (this.downloadQueue[i].progress >= 100) {
                        this.downloadQueue[i].progress = 100;
                        this.downloadQueue[i].status = 'completed';
                        this.completedVideos++;
                        clearInterval(downloadInterval);
                    }
                }, 200);

                // Wait for this video to complete before starting next
                await new Promise(resolve => {
                    const checkComplete = setInterval(() => {
                        if (this.downloadQueue[i].status === 'completed') {
                            clearInterval(checkComplete);
                            resolve();
                        }
                    }, 100);
                });
            }

            // All downloads completed
            setTimeout(() => {
                this.downloading = false;
                alert(`Playlist download completed! ${this.totalVideos} videos downloaded.`);
            }, 1000);
        }
    }
}
</script>
@endsection

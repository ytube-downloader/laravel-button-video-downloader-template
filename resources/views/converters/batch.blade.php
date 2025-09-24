@extends('layouts.app')

@section('title', 'Batch Converter - Convert Multiple Files')
@section('description', 'Convert multiple media files simultaneously. Batch processing for efficient file conversion.')

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

    <!-- Batch Converter Interface -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8" x-data="batchConverter()">
            <!-- File Upload Area -->
            <div class="mb-8">
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-12 text-center hover:border-purple-500 transition-colors">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 13h6m-3-3v6"></path>
                    </svg>
                    <input type="file" multiple class="hidden" x-ref="fileInput" @change="handleFileSelect" accept="video/*,audio/*">
                    <button type="button" @click="$refs.fileInput.click()" class="mb-4">
                        <span class="text-2xl theme-heading dark:theme-heading font-semibold block mb-2">Drop multiple files here</span>
                        <span class="text-lg theme-base dark:theme-base">or click to select files</span>
                    </button>
                    <p class="text-sm theme-base dark:theme-base">
                        Supports video and audio files. Maximum {{ count($pageData['features']) }} files at once.
                    </p>
                </div>
            </div>

            <!-- File Queue -->
            <div x-show="files.length > 0" class="mb-8">
                <h3 class="text-lg font-semibold theme-heading dark:theme-heading mb-4">File Queue</h3>
                <div class="space-y-3">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-800 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-medium theme-purple" x-text="(index + 1)"></span>
                                </div>
                                <div>
                                    <p class="font-medium theme-heading dark:theme-heading" x-text="file.name"></p>
                                    <p class="text-sm theme-base dark:theme-base" x-text="formatFileSize(file.size)"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <select x-model="file.outputFormat" class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800">
                                    <option value="mp4">MP4</option>
                                    <option value="mp3">MP3</option>
                                    <option value="wav">WAV</option>
                                    <option value="avi">AVI</option>
                                </select>
                                <button @click="removeFile(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Conversion Controls -->
            <div x-show="files.length > 0" class="text-center">
                <button 
                    @click="startBatchConversion()"
                    :disabled="converting"
                    class="theme-purple-bg text-white px-8 py-4 rounded-lg text-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50"
                >
                    <span x-show="!converting">Start Batch Conversion</span>
                    <span x-show="converting">Converting...</span>
                </button>
            </div>

            <!-- Progress Display -->
            <div x-show="converting" class="mt-8">
                <div class="space-y-3">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm theme-heading dark:theme-heading min-w-0 flex-1 truncate" x-text="file.name"></span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="theme-purple-bg h-2 rounded-full transition-all duration-300" :style="`width: ${file.progress || 0}%`"></div>
                            </div>
                            <span class="text-sm theme-base dark:theme-base w-12" x-text="(file.progress || 0) + '%'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-8 mt-16">
            @foreach($pageData['features'] as $feature)
                <div class="text-center p-6">
                    <div class="w-16 h-16 theme-purple-bg rounded-full flex items-center justify-center mx-auto mb-4">
                        <div class="w-8 h-8 bg-white rounded"></div>
                    </div>
                    <h3 class="text-xl font-semibold theme-heading dark:theme-heading mb-2">{{ $feature }}</h3>
                    <p class="theme-base dark:theme-base">Enhanced batch processing capability for efficient workflow management.</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function batchConverter() {
    return {
        files: [],
        converting: false,

        handleFileSelect(event) {
            const newFiles = Array.from(event.target.files).map(file => ({
                ...file,
                outputFormat: file.type.startsWith('video/') ? 'mp4' : 'mp3',
                progress: 0
            }));
            this.files = [...this.files, ...newFiles];
        },

        removeFile(index) {
            this.files.splice(index, 1);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        async startBatchConversion() {
            this.converting = true;
            
            // Simulate conversion progress for each file
            this.files.forEach((file, index) => {
                const interval = setInterval(() => {
                    file.progress += Math.random() * 15;
                    if (file.progress >= 100) {
                        file.progress = 100;
                        clearInterval(interval);
                        
                        // Check if all files are done
                        if (this.files.every(f => f.progress >= 100)) {
                            this.converting = false;
                            setTimeout(() => {
                                alert('All conversions completed! Download links prepared.');
                            }, 500);
                        }
                    }
                }, 300 + Math.random() * 200);
            });
        }
    }
}
</script>
@endsection

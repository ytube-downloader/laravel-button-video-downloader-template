# Laravel 12 Video Downloader Template

A modern, responsive video downloader web application built with Laravel 12. This application provides a clean, professional interface for downloading videos from popular platforms in various qualities and formats.

## 🎯 Features

### **Core Download Features**
- **4K Video Downloads** - Ultra HD quality downloads
- **Video to MP3/Audio** - Extract audio in multiple formats  
- **Playlist/Batch Downloads** - Download entire playlists or collections
- **HD 1080p Downloads** - Full HD quality downloads
- **Audio to WAV** - Lossless audio extraction

### **Technical Features**
- **Laravel 12** framework with modern architecture
- **Responsive Design** with Tailwind CSS and dark mode
- **Alpine.js** for interactive UI components
- **Progress Tracking** for download status
- **Rate Limiting** to prevent abuse
- **Database Logging** of all download activities
- **RESTful API** endpoints for external integration

## 🚀 Installation

### Prerequisites
- **PHP 8.3+**
- **Composer**
- **Node.js & NPM** 
- **Database** (SQLite/MySQL/PostgreSQL)

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-video-downloader
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   ```

5. **Build assets & start server**
   ```bash
   npm run build
   php artisan serve
   ```

Visit `http://localhost:8000` to see your video downloader!

## 🎨 Route Structure

| Original React Route | Laravel Route | Description |
|---------------------|---------------|-------------|
| `/` | `/` | Home page with main download interface |
| `/4k-video-downloader` | `/4k-video-downloader` | 4K video downloads |
| `/youtube-to-mp3` | `/video-to-mp3` | Video to audio conversion |
| `/youtube-playlist-downloader` | `/playlist-downloader` | Batch/playlist downloads |
| `/youtube-to-wav` | `/video-to-wav` | High-quality WAV extraction |
| `/youtube-1080p-downloader` | `/video-1080p-downloader` | 1080p HD downloads |

## 📱 UI Components

### **Main Interface**
- **Hero Section** with large URL input and instant download
- **Video Preview** showing thumbnail, title, duration
- **Quality/Format Selector** with real-time options
- **Progress Tracking** with animated progress bars
- **Quick Access** buttons for different download types

### **Individual Pages**
- **4K Downloader** - Ultra HD with quality options
- **Audio Extractor** - Multiple audio formats and bitrates
- **Playlist Downloader** - Batch processing with queue management
- **WAV Converter** - Professional audio settings
- **1080p Downloader** - Full HD with advanced options

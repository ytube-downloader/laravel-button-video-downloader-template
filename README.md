# Laravel 12 Media Converter Template

A modern, responsive media conversion web application built with Laravel 12, based on the React YouTube downloader template structure. This template provides a clean, professional interface for media file conversion with multiple format support.

## Features

- **Multiple Conversion Types**
  - 4K Video Conversion
  - Audio Conversion (MP3, WAV, FLAC, etc.)
  - Batch File Processing
  - Audio to WAV Conversion
  - 1080p Video Conversion

- **Modern UI/UX**
  - Responsive design with Tailwind CSS
  - Dark mode support
  - Smooth animations and transitions
  - Interactive file upload areas
  - Progress tracking

- **Technical Features**
  - Laravel 12 framework
  - Alpine.js for interactivity
  - File upload and processing
  - Queue system for background processing
  - Database tracking of conversions
  - RESTful API endpoints

## Installation

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-media-converter
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

## Customization

### Color Scheme
The color scheme can be customized by modifying the CSS variables in `resources/views/layouts/app.blade.php`:

```css
:root {
    --color-purple-main: #6c5ce7;
    --color-heading-main: #2D3436;
    --color-base-one: #4A5455;
    /* ... other colors */
}
```

### Adding New Conversion Types
1. Create a new controller method in `ConverterController`
2. Add a new route in `routes/web.php`
3. Create a corresponding Blade view in `resources/views/converters/`
4. Update the navigation links in the layout

### File Processing
Implement actual file conversion logic in the `ConversionService` class. The template provides a structure for:
- File upload handling
- Conversion status tracking
- Background job processing
- Error handling

## Routes

- `/` - Home page with main conversion interface
- `/4k-video-converter` - 4K video conversion
- `/audio-converter` - Audio format conversion
- `/batch-converter` - Multiple file processing
- `/audio-to-wav` - Audio to WAV conversion
- `/video-1080p-converter` - 1080p video conversion
- `/api/convert` - API endpoint for file conversion

## File Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   └── ConverterController.php
│   ├── Models/
│   │   └── Conversion.php
│   └── Services/
│       └── ConversionService.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── converters/
│       │   ├── 4k-video.blade.php
│       │   ├── audio.blade.php
│       │   ├── batch.blade.php
│       │   ├── audio-wav.blade.php
│       │   └── 1080p.blade.php
│       └── home.blade.php
├── database/
│   └── migrations/
│       └── create_conversions_table.php
└── routes/
    └── web.php
```

## API Usage

### Convert File
```bash
POST /api/convert
Content-Type: multipart/form-data

{
  "file": <file>,
  "format": "mp4",
  "quality": "1080p"
}
```

### Response
```json
{
  "success": true,
  "message": "File conversion started successfully",
  "conversion_id": "uuid-string",
  "estimated_time": 120
}
```

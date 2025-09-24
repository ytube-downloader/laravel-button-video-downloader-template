<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'download_id',
        'video_url',
        'video_title',
        'video_duration',
        'quality',
        'format',
        'file_size',
        'status',
        'download_path',
        'error_message',
        'metadata',
        'started_at',
        'completed_at',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    protected function fileSize(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $this->formatBytes($value) : null,
        );
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'video_download_api' => [
        'key' => env('VIDEO_DOWNLOAD_API_KEY'),
        'timeout' => env('VIDEO_DOWNLOAD_API_TIMEOUT', 120),
        'retry_times' => env('VIDEO_DOWNLOAD_API_RETRY', 3),
        'base_url' => env('VIDEO_DOWNLOAD_API_BASE_URL', 'https://p.savenow.to/ajax/download.php'),
        'rate_limit' => env('VIDEO_DOWNLOAD_API_RATE_LIMIT', 100), // requests per hour
    ],

];

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateVideoUrl
{
    private array $supportedDomains = [
        'youtube.com',
        'youtu.be', 
        'vimeo.com',
        'dailymotion.com',
        'facebook.com',
        'instagram.com',
        'tiktok.com',
        'twitter.com',
        'twitch.tv'
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('url')) {
            $url = $request->input('url');
            
            // Basic URL validation
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid URL format'
                ], 400);
            }

            // Check if domain is supported
            $isSupported = false;
            foreach ($this->supportedDomains as $domain) {
                if (strpos($url, $domain) !== false) {
                    $isSupported = true;
                    break;
                }
            }

            if (!$isSupported) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unsupported platform. Please use a supported video platform.',
                    'supported_platforms' => $this->supportedDomains
                ], 400);
            }
        }

        return $next($request);
    }
}
<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\VideoDownloadApiClient;
use App\Services\EnhancedVideoDownloadService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the API client as a singleton
        $this->app->singleton(VideoDownloadApiClient::class, function ($app) {
            return new VideoDownloadApiClient(
                config('services.video_download_api.key'),
                [
                    'timeout' => config('services.video_download_api.timeout', 120),
                    'retry_times' => config('services.video_download_api.retry_times', 3),
                ]
            );
        });

        // Register the enhanced download service
        $this->app->singleton(EnhancedVideoDownloadService::class, function ($app) {
            return new EnhancedVideoDownloadService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace YouTrackClient\Providers;

use Illuminate\Support\ServiceProvider;
use YouTrackClient\YouTrackClient;

class YouTrackClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(YouTrackClient::class, function ($app) {
            return new YouTrackClient([
                'baseUrl' => env('YT_BASE_URL').'/api',
                'hubUrl' => (env('YT_HUB_URL') ?? env('YT_BASE_URL').'/hub').'/api/rest',
                'token' => env('YT_TOKEN')
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    protected function registerPublishing() {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../../config' => config_path()], 'youtrack-client-config');
        }
    }
}

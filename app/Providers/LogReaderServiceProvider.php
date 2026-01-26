<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LogReaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config from package
        $this->mergeConfigFrom(
            base_path('vendor/haruncpi/laravel-log-reader/config/laravel-log-reader.php'),
            'laravel-log-reader'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config if needed
        $this->publishes([
            base_path('vendor/haruncpi/laravel-log-reader/config/laravel-log-reader.php') => config_path('laravel-log-reader.php'),
        ], 'config');

        // Load views from package
        $this->loadViewsFrom(
            base_path('vendor/haruncpi/laravel-log-reader/views'),
            'LaravelLogReader'
        );

        // DO NOT load routes - we register them manually in web.php
        // This prevents route conflicts
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        Config::$serverKey = 'Mid-server-xm5PqMZAVRHuNNVGFkNNNoAi';
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}

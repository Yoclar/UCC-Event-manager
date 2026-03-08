<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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
        RateLimiter::for('login', function (Request $request) {
        $email = (string) $request->input('email');
        $ip = $request->ip();

        return [
            Limit::perMinute(5)->by($ip), // max 5 failed attempt per minute by IP
            Limit::perMinute(5)->by('email:' . $email), // max 5 failed attempt per minute for a specific email
            Limit::perHour(20)->by($ip), // max 20 failed attempt per hour by IP (hard limit)
        ];
    });

    }
}

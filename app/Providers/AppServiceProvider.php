<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tren host (Render) trang chay qua HTTPS, nen ep Laravel tao link https
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

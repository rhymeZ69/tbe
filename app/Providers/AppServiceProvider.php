<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Only load settings if the table exists (avoids errors before migrations)
        if (Schema::hasTable('site_settings')) {
            $settings = cache()->remember('site_settings', 3600, function () {
                return SiteSetting::pluck('value', 'key')->toArray();
            });

            // Available as $settings in every Blade view
            View::share('settings', $settings);

            // Available as setting('key') in PHP and Blade
            $GLOBALS['__tbe_settings'] = $settings;
        }
    }
}
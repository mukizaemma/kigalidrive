<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\About;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper functions
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }


public function boot(): void
{
    View::share('destinations', cache()->remember('shared_destinations', 60 * 60, function () {
        try {
            return \App\Models\Category::oldest()->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }));

    View::share('services', collect());

    View::share('setting', cache()->remember('shared_setting', 60 * 60, function () {
        try {
            return \App\Models\Setting::first();
        } catch (\Throwable $e) {
            return null;
        }
    }));

    View::share('about', cache()->remember('shared_about', 60 * 60, function () {
        try {
            return \App\Models\About::first();
        } catch (\Throwable $e) {
            return null;
        }
    }));

    View::share('tripDestinations', collect());

    View::share('searchLocations', cache()->remember('shared_search_locations', 60 * 60, function () {
        try {
            $loc = \App\Models\Property::publishedForGuests()->whereNotNull('location')->distinct()->pluck('location');
            if ($loc->isEmpty()) {
                $loc = \App\Models\Hotel::whereNotNull('location')->where('status', 'Active')->where('is_listing_visible', true)->distinct()->pluck('location');
            }
            return $loc;
        } catch (\Throwable $e) {
            return collect();
        }
    }));
}

}

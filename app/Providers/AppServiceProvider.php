<?php

namespace App\Providers;

use App\Models\ApplicationSettings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::forever('application_settings', ApplicationSettings::all()->pluck('value', 'key'));

        Gate::before(function ($user) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}

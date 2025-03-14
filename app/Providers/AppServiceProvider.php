<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DestinationService;
use \App\Services\DestinationServiceInteface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DestinationServiceInteface::class, DestinationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

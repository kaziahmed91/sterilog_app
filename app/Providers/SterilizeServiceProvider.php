<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\SterilizerPrintService;
use App\Services\SterilizerService;

class SterilizeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('SterilizerPrintService', function ($app)
        {
            return new SterilizerPrintService();
        });

        $this->app->singleton('SterilizerService', function ($app) 
        {
            return new SterilizerService();
        })
    }
}

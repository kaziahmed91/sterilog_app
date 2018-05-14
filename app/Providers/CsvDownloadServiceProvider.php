<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CsvDownloadService;

class CsvDownloadServiceProvider extends ServiceProvider
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
        $this->app->singleton('CsvDownloadService', function ($app)
        {
            return new CsvDownloadService();
        });


    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // ''
        // error_log('registering');
        view()->composer('*','App\Http\ViewComposers\UserDataComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}

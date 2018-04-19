<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Auth;
use Schema;
use Illuminate\Support\Facades\Auth;
use App\SoftUser as SoftUserModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // App::bind()
        // App::singleton('\Services\FpdfLabelService', function () {
        //     return new \Services\FpdfLabelService()
        // })
    }
}

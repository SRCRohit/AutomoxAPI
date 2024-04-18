<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        $host = request()->getHttpHost();
        if($host != 'automox.srccybersolutions.com'){
            print('Please contact to support to register new domain');
            exit;
        }
        Schema::defaultStringLength(191);
    }
}

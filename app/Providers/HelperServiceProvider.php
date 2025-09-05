<?php
// app/Providers/HelperServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register helper files
        $this->app->singleton('RoleHelper', function () {
            return new \App\Helpers\RoleHelper();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
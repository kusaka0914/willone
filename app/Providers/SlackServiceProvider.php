<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Managers\SlackServiceManager;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('slack', SlackServiceManager::class);
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

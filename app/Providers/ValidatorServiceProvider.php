<?php

namespace App\Providers;

use App\Http\Validators\CustomValidator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, array $data, array $rules, array $messages, array $attributes) {
            return new CustomValidator($translator, $data, $rules, $messages, $attributes);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

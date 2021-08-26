<?php

namespace App\Providers;

use App\Services\Telegram\Handlers\UpdateHandler;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;
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
        LaravelJsonApi::defaultApi('v1');
        $this->app->bind(
            UpdateHandler::class
        );
    }
}

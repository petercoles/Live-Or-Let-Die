<?php

namespace PeterColes\LiveOrLetDie\Providers;

use Illuminate\Support\ServiceProvider;

class LiveOrLetDieServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }

        $this->publishes([
            __DIR__.'/../../config/liveorletdie.php' => config_path('liveorletdie.php'),
        ]);
    }
}

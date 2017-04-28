<?php

namespace PeterColes\LiveOrLetDie\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

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
    public function boot(Router $router)
    {
        // load package routes
        $this->loadRoutesFrom(__DIR__.'/../../routes.php');

        // make config file available if needed
        $this->publishes([
            __DIR__.'/../../config/liveorletdie.php' => config_path('liveorletdie.php'),
        ]);

        // register package middleware
        $router->pushMiddlewareToGroup('web', \PeterColes\LiveOrLetDie\Middleware\SessionTimeout::class);
    }
}

<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\ServiceProvider;

class TickTockServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ticktock.php', 'ticktock');

        $this->app->singleton(TickTock::class, function ($app) {
            return new TickTock($app->make(Clock::class), $app['config']->get('ticktock.enabled', true));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ticktock.php' => config_path('ticktock.php'),
        ], 'ticktock-config');

        // Long-running processes reuse the singleton, so start each job/request with a clean tree
        $this->app['events']->listen(
            ['Illuminate\Queue\Events\JobProcessing', 'Laravel\Octane\Events\RequestReceived'],
            function () {
                Facades\TickTock::getFacadeRoot()->reset();
            }
        );
    }
}

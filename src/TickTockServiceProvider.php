<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\ServiceProvider;

class TickTockServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TickTock::class);
    }

    public function boot()
    {
        // Long-running processes reuse the singleton, so start each job/request with a clean tree
        $this->app['events']->listen(
            ['Illuminate\Queue\Events\JobProcessing', 'Laravel\Octane\Events\RequestReceived'],
            function () {
                Facades\TickTock::getFacadeRoot()->reset();
            }
        );
    }
}

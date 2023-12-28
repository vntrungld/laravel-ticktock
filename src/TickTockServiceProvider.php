<?php

namespace Vntrungld\LaravelTicktock;

class TickTockServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/ticktock.php' => config_path('ticktock.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ticktock.php', 'ticktock');

        $this->app->singleton(TickTock::class);
    }
}

<?php

namespace Vntrungld\LaravelTicktock;

class TickTockServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TickTock::class);
    }
}

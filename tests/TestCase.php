<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Vntrungld\LaravelTicktock\TickTockServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            TickTockServiceProvider::class,
        ];
    }
}

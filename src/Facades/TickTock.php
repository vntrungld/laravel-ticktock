<?php

namespace Vntrungld\LaravelTicktock\Facades;

use Illuminate\Support\Facades\Facade;

class TickTock extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vntrungld\LaravelTicktock\TickTock::class;
    }
}

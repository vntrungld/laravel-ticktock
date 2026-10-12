<?php

namespace Vntrungld\LaravelTicktock\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void start(string|null $suffix = null)
 * @method static float|null capture(string|null $suffix = null)
 * @method static float|null end()
 * @method static mixed measure(string|null $suffix, callable $callback)
 * @method static void reset()
 * @method static bool isEnabled()
 * @method static void dump()
 * @method static void dd()
 * @method static void log()
 * @method static string render()
 *
 * @see \Vntrungld\LaravelTicktock\TickTock
 */
class TickTock extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Vntrungld\LaravelTicktock\TickTock::class;
    }
}

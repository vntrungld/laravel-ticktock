<?php

use Vntrungld\LaravelTicktock\Facades\TickTock;

if (! function_exists('tts')) {
    function tts($name = null)
    {
        return TickTock::start($name);
    }
}

if (! function_exists('tte')) {
    function tte()
    {
        return TickTock::end();
    }
}

if (! function_exists('tt')) {
    function tt($name = null)
    {
        return TickTock::capture($name);
    }
}

if (! function_exists('ttdd')) {
    function ttdd()
    {
        return TickTock::dd();
    }
}

if (! function_exists('ttd')) {
    function ttd()
    {
        return TickTock::dump();
    }
}

if (! function_exists('ttl')) {
    function ttl()
    {
        return TickTock::log();
    }
}

if (! function_exists('ttr')) {
    function ttr()
    {
        return TickTock::render();
    }
}

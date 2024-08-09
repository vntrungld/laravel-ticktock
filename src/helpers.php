<?php

if (! function_exists('tts')) {
    function tts($name)
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::start($name);
    }
}

if (! function_exists('tte')) {
    function tte()
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::end();
    }
}

if (! function_exists('tt')) {
    function tt($name)
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::capture($name);
    }
}

if (! function_exists('ttdd')) {
    function ttdd()
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::dd();
    }
}

if (! function_exists('ttd')) {
    function ttd()
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::dump();
    }
}

if (! function_exists('ttl')) {
    function ttl()
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::log();
    }
}

if (! function_exists('ttr')) {
    function ttr()
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::render();
    }
}

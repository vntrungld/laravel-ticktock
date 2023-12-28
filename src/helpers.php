<?php

if (! function_exists('tick')) {
    function tick($name)
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::start($name);
    }
}

if (! function_exists('tock')) {
    function tock($name)
    {
        return \Vntrungld\LaravelTicktock\Facades\TickTock::end($name);
    }
}

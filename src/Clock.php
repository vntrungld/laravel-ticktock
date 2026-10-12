<?php

namespace Vntrungld\LaravelTicktock;

class Clock
{
    /**
     * Get the current time in milliseconds
     *
     * Uses the monotonic hrtime() when available (PHP 7.3+).
     *
     * @return float
     */
    public function now(): float
    {
        if (function_exists('hrtime')) {
            return hrtime(true) / 1e6;
        }

        return microtime(true) * 1000;
    }
}

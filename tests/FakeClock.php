<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Vntrungld\LaravelTicktock\Clock;

class FakeClock extends Clock
{
    /**
     * @var float
     */
    protected $now = 0.0;

    public function now(): float
    {
        return $this->now;
    }

    /**
     * Move the clock forward
     *
     * @param float $milliseconds
     * @return void
     */
    public function advance($milliseconds)
    {
        $this->now += $milliseconds;
    }
}

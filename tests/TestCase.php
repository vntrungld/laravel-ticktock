<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Vntrungld\LaravelTicktock\Clock;
use Vntrungld\LaravelTicktock\TickTockServiceProvider;

class TestCase extends Orchestra
{
    /**
     * @var FakeClock
     */
    protected $clock;

    protected function getEnvironmentSetUp($app)
    {
        $this->clock = new FakeClock();

        $app->instance(Clock::class, $this->clock);
    }

    protected function getPackageProviders($app)
    {
        return [
            TickTockServiceProvider::class,
        ];
    }

    /**
     * Assert that a string contains another string.
     * Compatibility wrapper for PHPUnit 6.x - 11.x.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertOutputContains($needle, $haystack, $message = '')
    {
        if (method_exists(get_parent_class($this), 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
            return;
        }

        $this->assertContains($needle, $haystack, $message);
    }
}

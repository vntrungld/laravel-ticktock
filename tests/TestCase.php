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

    /**
     * Assert that two values are equal within a delta.
     * Compatibility wrapper for PHPUnit 6.x - 11.x.
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param float $delta
     * @param string $message
     */
    protected function assertDurationEquals($expected, $actual, $delta, $message = '')
    {
        if (method_exists(get_parent_class($this), 'assertEqualsWithDelta')) {
            $this->assertEqualsWithDelta($expected, $actual, $delta, $message);
            return;
        }

        $this->assertEquals($expected, $actual, $message, $delta);
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

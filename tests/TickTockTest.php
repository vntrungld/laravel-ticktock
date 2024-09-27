<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Illuminate\Support\Facades\Log;
use Vntrungld\LaravelTicktock\Facades\TickTock;

class TickTockTest extends TestCase
{
    protected function parentMethod()
    {
        tts('parent suffix');
        usleep(10 * 1000); tt();
        usleep(20 * 1000); tt();
        $this->childMethod1();
        $this->childMethod2();
        $duration = tte();

        $this->assertEqualsWithDelta(130, $duration, 5);
    }

    protected function childMethod1()
    {
        tts();
        usleep(5 * 1000); tt('child suffix');
        usleep(7 * 1000); tt();
        $this->childMethod13();
        usleep(18 * 1000); tt();
        $this->childMethod15();
        $duration = tte();

        $this->assertEqualsWithDelta(56, $duration, 3);
    }

    protected function childMethod13()
    {
        tts();
        usleep(3 * 1000); tt();
        usleep(4 * 1000); tt();
        $duration = tte();

        $this->assertEqualsWithDelta(7, $duration, 1);
    }

    protected function childMethod15()
    {
        tts();
        usleep(8 * 1000); tt();
        usleep(9 * 1000); tt();
        $duration = tte();

        $this->assertEqualsWithDelta(17, $duration, 1);
    }

    protected function childMethod2()
    {
        tts();
        usleep(10 * 1000); tt();
        usleep(15 * 1000); tt();
        usleep(20 * 1000); tt();
        $duration = tte();

        $this->assertEqualsWithDelta(45, $duration, 1);
    }

    public function test_print()
    {
        $this->parentMethod();
        $output = ttr();
        $this->assertStringContainsString('Vntrungld\LaravelTicktock\Tests\TickTockTest::parentMethod - parent suffix', $output);
        $this->assertStringContainsString('├── Line 13', $output);
        $this->assertStringContainsString('├── Line 14', $output);
        $this->assertStringContainsString('├── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod1', $output);
        $this->assertStringContainsString('│   ├── Line 25 - child suffix', $output);
        $this->assertStringContainsString('│   ├── Line 26', $output);
        $this->assertStringContainsString('│   ├── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod13', $output);
        $this->assertStringContainsString('│   │   ├── Line 38', $output);
        $this->assertStringContainsString('│   │   └── Line 39', $output);
        $this->assertStringContainsString('│   ├── Line 28', $output);
        $this->assertStringContainsString('│   └── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod15', $output);
        $this->assertStringContainsString('│       ├── Line 48', $output);
        $this->assertStringContainsString('│       └── Line 49', $output);
        $this->assertStringContainsString('└── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod2', $output);
        $this->assertStringContainsString('    ├── Line 58', $output);
        $this->assertStringContainsString('    ├── Line 59', $output);
        $this->assertStringContainsString('    └── Line 60', $output);
    }
}

<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Illuminate\Support\Facades\Log;

class TickTockTest extends TestCase
{
    public function test_can_calculate_multiple_processing_times_in_milliseconds()
    {
        config(['ticktock.unit' => 'ms']);
        tick('test1');
        sleep(1);
        $delta_time1 = tock('test1');
        $this->assertGreaterThan(1000, $delta_time1);
        $this->assertLessThan(1001, $delta_time1);

        tick('test2');
        sleep(1);
        $delta_time2 = tock('test2');
        $this->assertGreaterThan(1000, $delta_time2);
        $this->assertLessThan(1001, $delta_time2);
    }

    public function test_can_calculate_multiple_processing_times_in_seconds()
    {
        config(['ticktock.unit' => 's']);
        tick('test1');
        sleep(1);
        $delta_time1 = tock('test1');
        $this->assertGreaterThan(1, $delta_time1);
        $this->assertLessThan(1.001, $delta_time1);

        tick('test2');
        sleep(1);
        $delta_time2 = tock('test2');
        $this->assertGreaterThan(1, $delta_time2);
        $this->assertLessThan(1.001, $delta_time2);
    }

    public function test_cannot_calculate_processing_time_of_a_non_existing_timer()
    {
        $delta_time = tock('test3');
        $this->assertNull($delta_time);
    }

    public function test_can_log_processing_time()
    {
        $log = Log::shouldReceive('debug');

        config()->set('ticktock.log', true);
        tick('test');
        sleep(1);
        tock('test');

        $log->once();
    }
}

<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Vntrungld\LaravelTicktock\Clock;
use Vntrungld\LaravelTicktock\Facades\TickTock;
use Vntrungld\LaravelTicktock\Node;

class TickTockTest extends TestCase
{
    /**
     * @var array<string, int>
     */
    protected $lines = [];

    protected function parentMethod()
    {
        tts('parent suffix');
        $this->clock->advance(10); tt(); $this->lines['p1'] = __LINE__;
        $this->clock->advance(20); tt(); $this->lines['p2'] = __LINE__;
        $this->childMethod1();
        $this->childMethod2();

        return tte();
    }

    protected function childMethod1()
    {
        tts();
        $this->clock->advance(5); tt('child suffix'); $this->lines['c1'] = __LINE__;
        $this->clock->advance(7); tt(); $this->lines['c2'] = __LINE__;
        $this->childMethod13();
        $this->clock->advance(18); tt(); $this->lines['c4'] = __LINE__;
        $this->childMethod15();

        return tte();
    }

    protected function childMethod13()
    {
        tts();
        $this->clock->advance(3); tt(); $this->lines['c31'] = __LINE__;
        $this->clock->advance(4); tt(); $this->lines['c32'] = __LINE__;

        return tte();
    }

    protected function childMethod15()
    {
        tts();
        $this->clock->advance(8); tt(); $this->lines['c51'] = __LINE__;
        $this->clock->advance(9); tt(); $this->lines['c52'] = __LINE__;

        return tte();
    }

    protected function childMethod2()
    {
        tts();
        $this->clock->advance(10); tt(); $this->lines['d1'] = __LINE__;
        $this->clock->advance(15); tt(); $this->lines['d2'] = __LINE__;
        $this->clock->advance(20); tt(); $this->lines['d3'] = __LINE__;

        return tte();
    }

    public function test_print()
    {
        $this->assertSame(129.0, $this->parentMethod());

        $class = self::class;
        $l = $this->lines;
        $expected = implode(PHP_EOL, [
            "$class::parentMethod - parent suffix -- 129.00ms",
            "├── Line {$l['p1']} -- 10.00ms",
            "├── Line {$l['p2']} -- 20.00ms",
            "├── $class::childMethod1 -- 54.00ms",
            "│   ├── Line {$l['c1']} - child suffix -- 5.00ms",
            "│   ├── Line {$l['c2']} -- 7.00ms",
            "│   ├── $class::childMethod13 -- 7.00ms",
            "│   │   ├── Line {$l['c31']} -- 3.00ms",
            "│   │   └── Line {$l['c32']} -- 4.00ms",
            "│   ├── Line {$l['c4']} -- 18.00ms",
            "│   └── $class::childMethod15 -- 17.00ms",
            "│       ├── Line {$l['c51']} -- 8.00ms",
            "│       └── Line {$l['c52']} -- 9.00ms",
            "└── $class::childMethod2 -- 45.00ms",
            "    ├── Line {$l['d1']} -- 10.00ms",
            "    ├── Line {$l['d2']} -- 15.00ms",
            "    └── Line {$l['d3']} -- 20.00ms",
        ]).PHP_EOL;

        $this->assertSame($expected, ttr());
    }

    public function test_facade_names_the_calling_method()
    {
        TickTock::start('single');
        $this->clock->advance(10); TickTock::capture('step1'); $line = __LINE__;
        $this->clock->advance(20);

        $this->assertSame(30.0, TickTock::end());
        $this->assertSame(
            self::class.'::test_facade_names_the_calling_method - single -- 30.00ms'.PHP_EOL
            ."└── Line $line - step1 -- 10.00ms".PHP_EOL,
            TickTock::render()
        );
    }

    public function test_direct_instance_names_the_calling_method()
    {
        $ticktock = app(\Vntrungld\LaravelTicktock\TickTock::class);
        $ticktock->start('direct');
        $ticktock->end();

        $this->assertOutputContains(self::class.'::test_direct_instance_names_the_calling_method - direct', $ticktock->render());
    }

    public function test_global_function_caller()
    {
        require_once __DIR__.'/Fixtures/functions.php';

        ticktock_fixture_global_function();

        $this->assertOutputContains('ticktock_fixture_global_function - global -- ', ttr());
    }

    public function test_calls_without_start_do_not_crash()
    {
        $this->assertNull(tte());
        $this->assertNull(tt());
        $this->assertSame('', ttr());
    }

    public function test_measure_returns_callback_result()
    {
        $clock = $this->clock;
        $result = TickTock::measure('work', function () use ($clock) {
            $clock->advance(12);

            return 'done';
        });

        $this->assertSame('done', $result);
        $this->assertSame(self::class.'::test_measure_returns_callback_result - work -- 12.00ms'.PHP_EOL, ttr());
    }

    public function test_measure_ends_timers_when_callback_throws()
    {
        try {
            TickTock::measure('outer', function () {
                tts('left open');
                throw new \RuntimeException('boom');
            });
            $this->fail('Exception was swallowed');
        } catch (\RuntimeException $e) {
            $this->assertSame('boom', $e->getMessage());
        }

        $this->assertFalse(strpos(ttr(), '(running)'), 'All timers should be ended');

        tts('next');
        tte();

        $this->assertStringStartsWith(self::class.'::test_measure_ends_timers_when_callback_throws - next', ttr());
    }

    public function test_render_marks_running_timers()
    {
        tts('open');
        $this->clock->advance(5);

        $this->assertOutputContains('- open -- 5.00ms (running)', ttr());
    }

    public function test_sub_millisecond_precision()
    {
        tts('fast');
        $this->clock->advance(0.25);

        $this->assertSame(0.25, tte());
        $this->assertOutputContains('- fast -- 0.25ms', ttr());
    }

    public function test_reset_on_queue_job()
    {
        tts('stale');

        event(new JobProcessing('sync', \Mockery::mock(Job::class)->shouldIgnoreMissing()));

        $this->assertSame('', ttr());
        $this->assertNull(tte());
    }

    public function test_composer_aliases_and_providers_exist()
    {
        $composer = json_decode(file_get_contents(__DIR__.'/../composer.json'), true);
        $laravel = $composer['extra']['laravel'];

        foreach (array_merge($laravel['providers'], array_values($laravel['aliases'])) as $class) {
            $this->assertTrue(class_exists($class), "Class [$class] does not exist");
        }
    }

    public function test_facade_resolves_singleton()
    {
        $instance1 = app(\Vntrungld\LaravelTicktock\TickTock::class);
        $instance2 = app(\Vntrungld\LaravelTicktock\TickTock::class);

        $this->assertSame($instance1, $instance2);
    }

    public function test_log_output()
    {
        Log::shouldReceive('debug')->once();

        tts('log-test');
        tt();
        tte();
        ttl();
    }

    public function test_nested_node_render()
    {
        $root = new Node('root', $this->clock);
        $root->start();

        $this->clock->advance(5);
        $root->addChild('child1')->end();

        $child2 = $root->addChild('child2');
        $this->clock->advance(3);
        $child2->addChild('grandchild1')->end();
        $this->clock->advance(4);
        $child2->addChild('grandchild2')->end();
        $child2->end();

        $root->end();

        $this->assertSame(implode(PHP_EOL, [
            'root -- 12.00ms',
            '├── child1 -- 5.00ms',
            '└── child2 -- 7.00ms',
            '    ├── grandchild1 -- 3.00ms',
            '    └── grandchild2 -- 4.00ms',
        ]).PHP_EOL, $root->render());
    }

    public function test_default_clock_is_monotonic_milliseconds()
    {
        $clock = new Clock;
        $first = $clock->now();
        usleep(2 * 1000);
        $elapsed = $clock->now() - $first;

        $this->assertGreaterThanOrEqual(1, $elapsed);
        $this->assertLessThan(1000, $elapsed);
    }
}

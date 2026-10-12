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

        $this->assertDurationEquals(130, $duration, 15);
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

        $this->assertDurationEquals(56, $duration, 10);
    }

    protected function childMethod13()
    {
        tts();
        usleep(3 * 1000); tt();
        usleep(4 * 1000); tt();
        $duration = tte();

        $this->assertDurationEquals(7, $duration, 5);
    }

    protected function childMethod15()
    {
        tts();
        usleep(8 * 1000); tt();
        usleep(9 * 1000); tt();
        $duration = tte();

        $this->assertDurationEquals(17, $duration, 5);
    }

    protected function childMethod2()
    {
        tts();
        usleep(10 * 1000); tt();
        usleep(15 * 1000); tt();
        usleep(20 * 1000); tt();
        $duration = tte();

        $this->assertDurationEquals(45, $duration, 10);
    }

    public function test_print()
    {
        $this->parentMethod();
        $output = ttr();
        $this->assertOutputContains('Vntrungld\LaravelTicktock\Tests\TickTockTest::parentMethod - parent suffix', $output);
        $this->assertOutputContains('├── Line 13', $output);
        $this->assertOutputContains('├── Line 14', $output);
        $this->assertOutputContains('├── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod1', $output);
        $this->assertOutputContains('│   ├── Line 25 - child suffix', $output);
        $this->assertOutputContains('│   ├── Line 26', $output);
        $this->assertOutputContains('│   ├── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod13', $output);
        $this->assertOutputContains('│   │   ├── Line 38', $output);
        $this->assertOutputContains('│   │   └── Line 39', $output);
        $this->assertOutputContains('│   ├── Line 28', $output);
        $this->assertOutputContains('│   └── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod15', $output);
        $this->assertOutputContains('│       ├── Line 48', $output);
        $this->assertOutputContains('│       └── Line 49', $output);
        $this->assertOutputContains('└── Vntrungld\LaravelTicktock\Tests\TickTockTest::childMethod2', $output);
        $this->assertOutputContains('    ├── Line 58', $output);
        $this->assertOutputContains('    ├── Line 59', $output);
        $this->assertOutputContains('    └── Line 60', $output);
    }

    public function test_node_creation_and_duration()
    {
        $node = new \Vntrungld\LaravelTicktock\Node('test-node');
        $node->start();
        usleep(10 * 1000);
        $node->end();

        $this->assertDurationEquals(10, $node->getDuration(), 5);
    }

    public function test_node_children_render()
    {
        $node = new \Vntrungld\LaravelTicktock\Node('root');
        $node->start();

        $child1 = $node->addChild('child1');
        usleep(5 * 1000);
        $child1->end();

        $child2 = $node->addChild('child2');
        usleep(10 * 1000);
        $child2->end();

        $node->end();

        $output = $node->render();
        $this->assertOutputContains('root', $output);
        $this->assertOutputContains('├── child1', $output);
        $this->assertOutputContains('└── child2', $output);
    }

    public function test_nested_node_render()
    {
        $root = new \Vntrungld\LaravelTicktock\Node('root');
        $root->start();

        $child1 = $root->addChild('child1');
        usleep(5 * 1000);
        $child1->end();

        $child2 = $root->addChild('child2');
        $grandchild1 = $child2->addChild('grandchild1');
        usleep(3 * 1000);
        $grandchild1->end();
        $grandchild2 = $child2->addChild('grandchild2');
        usleep(4 * 1000);
        $grandchild2->end();
        $child2->end();

        $root->end();

        $output = $root->render();
        $this->assertOutputContains('├── child1', $output);
        $this->assertOutputContains('└── child2', $output);
        $this->assertOutputContains('    ├── grandchild1', $output);
        $this->assertOutputContains('    └── grandchild2', $output);
    }

    public function test_facade_resolves_singleton()
    {
        $instance1 = app(\Vntrungld\LaravelTicktock\TickTock::class);
        $instance2 = app(\Vntrungld\LaravelTicktock\TickTock::class);

        $this->assertSame($instance1, $instance2);
    }

    public function test_single_level_timer()
    {
        $ticktock = app(\Vntrungld\LaravelTicktock\TickTock::class);
        TickTock::start('single');
        usleep(10 * 1000);
        TickTock::capture('step1');
        usleep(20 * 1000);
        TickTock::capture('step2');
        $duration = TickTock::end();

        $this->assertDurationEquals(30, $duration, 10);

        $output = TickTock::render();
        $this->assertOutputContains('single', $output);
        $this->assertOutputContains('step1', $output);
        $this->assertOutputContains('step2', $output);
    }

    public function test_log_output()
    {
        Log::shouldReceive('debug')->once();

        tts('log-test');
        usleep(5 * 1000);
        tt();
        tte();
        ttl();
    }

    public function test_render_contains_duration()
    {
        $node = new \Vntrungld\LaravelTicktock\Node('timer');
        $node->start();
        usleep(10 * 1000);
        $node->end();

        $output = $node->render();
        $this->assertOutputContains('ms', $output);
        $this->assertOutputContains('timer -- ', $output);
    }
}

<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class TickTock
{
    /**
     * @var Clock
     */
    protected $clock;

    /**
     * @var Node|null
     */
    protected $root;

    /**
     * @var array<Node>
     */
    protected $cursors = [];

    /**
     * @var string|null
     */
    protected static $facade_file;

    /**
     * TickTock constructor.
     */
    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * Start a timer
     *
     * @param  null  $suffix
     * @return void
     */
    public function start($suffix = null)
    {
        $name = $this->getCaller()['scope'].($suffix ? ' - '.$suffix : '');
        $current_node = end($this->cursors);

        if ($current_node) {
            $node = $current_node->addChild($name);
        } else {
            $node = new Node($name, $this->clock);
            $node->start();
            $this->root = $node;
        }

        $this->cursors[] = $node;
    }

    /**
     * Capture a timer
     *
     * @return float|null
     */
    public function capture($suffix = null)
    {
        $now = $this->clock->now();
        $current_node = end($this->cursors);

        if (! $current_node) {
            return null;
        }

        $name = 'Line '.$this->getCaller()['line'].($suffix ? ' - '.$suffix : '');

        $node = $current_node->addChild($name);
        $node->end($now);

        return $node->getDuration();
    }

    /**
     * End a timer
     *
     * @return float|null
     */
    public function end()
    {
        $now = $this->clock->now();
        $node = array_pop($this->cursors);

        if (! $node) {
            return null;
        }

        $node->end($now);

        return $node->getDuration();
    }

    /**
     * Time a callback, ending its timer even when the callback throws
     *
     * @param  string|null  $suffix
     * @return mixed
     */
    public function measure($suffix, callable $callback)
    {
        $depth = count($this->cursors);

        $this->start($suffix);

        try {
            return $callback();
        } finally {
            while (count($this->cursors) > $depth) {
                $this->end();
            }
        }
    }

    /**
     * Discard all timers
     *
     * @return void
     */
    public function reset()
    {
        $this->root = null;
        $this->cursors = [];
    }

    /**
     * Dump the tree
     *
     * @return void
     */
    public function dump()
    {
        dump($this->render());
    }

    /**
     * Dump the tree and die
     *
     * @return void
     */
    public function dd()
    {
        dd($this->render());
    }

    /**
     * Log the tree
     *
     * @return void
     */
    public function log()
    {
        Log::debug(PHP_EOL.'[Ticktock]'.PHP_EOL.$this->render());
    }

    /**
     * Render the tree
     */
    public function render(): string
    {
        return $this->root ? $this->root->render() : '';
    }

    /**
     * Find the line and enclosing scope of the first call made from outside
     * this package (skipping the helpers and the facade)
     *
     * @return array{line: int|null, scope: string}
     */
    protected function getCaller(): array
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 16);

        foreach ($backtrace as $index => $frame) {
            if (! isset($frame['file']) || $this->isInternalFile($frame['file'])) {
                continue;
            }

            $scope = isset($backtrace[$index + 1]) ? $backtrace[$index + 1] : null;

            if (! $scope || in_array($scope['function'], ['include', 'include_once', 'require', 'require_once'], true)) {
                return ['line' => $frame['line'], 'scope' => basename($frame['file'])];
            }

            $class = isset($scope['class']) ? $scope['class'].'::' : '';

            return ['line' => $frame['line'], 'scope' => $class.$scope['function']];
        }

        return ['line' => null, 'scope' => 'unknown'];
    }

    /**
     * Determine if a file belongs to this package or the facade base class
     *
     * @param  string  $file
     */
    protected function isInternalFile($file): bool
    {
        if (static::$facade_file === null) {
            static::$facade_file = (new \ReflectionClass(Facade::class))->getFileName();
        }

        return strpos($file, __DIR__.DIRECTORY_SEPARATOR) === 0 || $file === static::$facade_file;
    }
}

<?php

namespace Vntrungld\LaravelTicktock;

class Node
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Clock
     */
    protected $clock;

    /**
     * Start time in milliseconds
     *
     * @var float
     */
    protected $start_time;

    /**
     * End time in milliseconds
     *
     * @var float|null
     */
    protected $end_time;

    /**
     * @var array<Node>
     */
    protected $children = [];

    /**
     * Node constructor.
     *
     * @param  Clock|null  $clock
     */
    public function __construct(string $name, $clock = null)
    {
        $this->name = $name;
        $this->clock = $clock ?: new Clock;
    }

    /**
     * Set the start time
     *
     * @param  float  $time
     * @return void
     */
    public function setStartTime($time)
    {
        $this->start_time = $time;
    }

    /**
     * Start the timer
     *
     * @param  float|null  $time
     * @return void
     */
    public function start($time = null)
    {
        $this->start_time = $time === null ? $this->clock->now() : $time;
    }

    /**
     * End the timer
     *
     * @param  float|null  $time
     * @return void
     */
    public function end($time = null)
    {
        $this->end_time = $time === null ? $this->clock->now() : $time;
    }

    /**
     * Determine if the timer has not been ended yet
     */
    public function isRunning(): bool
    {
        return $this->end_time === null;
    }

    /**
     * Add a child node
     */
    public function addChild($name): Node
    {
        $node = new Node($name, $this->clock);
        $last_child = $this->getLastChild();

        $node->setStartTime($last_child ? $last_child->end_time : $this->start_time);

        $this->children[] = $node;

        return $node;
    }

    /**
     * Get the last child
     *
     * @return false|Node
     */
    public function getLastChild()
    {
        return end($this->children);
    }

    /**
     * Get the duration in milliseconds, measured up to now if still running
     */
    public function getDuration(): float
    {
        $end_time = $this->isRunning() ? $this->clock->now() : $this->end_time;

        return round($end_time - $this->start_time, 2);
    }

    /**
     * Render the node
     */
    public function render(string $parent_prefix = '', string $child_prefix = ''): string
    {
        $output = $parent_prefix.$this->name.' -- '.sprintf('%.2f', $this->getDuration()).'ms'
            .($this->isRunning() ? ' (running)' : '').PHP_EOL;

        if (empty($this->children)) {
            return $output;
        }

        $untracked = $this->getUntrackedDuration();
        $last_child = $untracked > 0 ? null : end($this->children);

        foreach ($this->children as $child) {
            if ($child === $last_child) {
                $output .= $child->render($child_prefix.'└── ', $child_prefix.'    ');
            } else {
                $output .= $child->render($child_prefix.'├── ', $child_prefix.'│   ');
            }
        }

        if ($untracked > 0) {
            $output .= $child_prefix.'└── (untracked) -- '.sprintf('%.2f', $untracked).'ms'.PHP_EOL;
        }

        return $output;
    }

    /**
     * Get the time between the last child's end and this node's end
     */
    public function getUntrackedDuration(): float
    {
        $last_child = $this->getLastChild();

        if (! $last_child || $this->isRunning() || $last_child->isRunning()) {
            return 0.0;
        }

        return round($this->end_time - $last_child->end_time, 2);
    }
}

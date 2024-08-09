<?php

namespace Vntrungld\LaravelTicktock;

class Node
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $start_time;

    /**
     * @var int
     */
    protected $end_time;

    /**
     * @var array<Node>
     */
    protected $children = [];


    /**
     * Node constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set the start time
     *
     * @param $time
     * @return void
     */
    public function setStartTime($time)
    {
        $this->start_time = $time;
    }

    /**
     * Start the timer
     *
     * @return void
     */
    public function start()
    {
        $this->start_time = microtime(true);
    }

    /**
     * End the timer
     *
     * @return void
     */
    public function end()
    {
        $this->end_time = microtime(true);
    }

    /**
     * Add a child node
     *
     * @param $name
     * @return Node
     */
    public function addChild($name): Node
    {
        $node = new Node($name);

        if (empty($this->children)) {
            $node->setStartTime($this->start_time);
        } else {
            $node->setStartTime($this->getLastChild()->end_time);
        }

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
     * Get the duration
     *
     * @return float
     */
    public function getDuration(): float
    {
        return round(($this->end_time - $this->start_time) * 1000);
    }

    /**
     * Render the node
     *
     * @param string $parent_prefix
     * @param string $child_prefix
     * @return string
     */
    public function render(string $parent_prefix = '', string $child_prefix = ''): string
    {
        $output = $parent_prefix . $this->name . ' -- ' . $this->getDuration() . 'ms' . PHP_EOL;

        if (empty($this->children)) {
            return $output;
        }

        $last_child = end($this->children);

        foreach ($this->children as $child) {
            if ($child === $last_child) {
                $output .= $child->render($child_prefix . '└── ', $child_prefix . '    ');
            } else {
                $output .= $child->render($child_prefix . '├── ', $child_prefix . '│   ');
            }
        }

        return $output;
    }
}

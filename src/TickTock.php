<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\Facades\Log;

class TickTock
{

    /**
     * @var Node
     */
    protected $root;

    /**
     * @var array<Node>
     */
    protected $cursors = [];

    /**
     * Start a timer
     */
    public function start($name = 'total')
    {
        if (empty($this->cursors)) {
            $node = new Node($name);
            $node->start();
            $this->root = $node;
        } else {
            $current_node = end($this->cursors);
            $node = $current_node->addChild($name);
        }

        $this->cursors[] = $node;
    }

    /**
     * Capture a timer
     *
     * @param $name
     * @return void
     */
    public function capture($name)
    {
        $current_node = end($this->cursors);
        $node = $current_node->addChild($name);
        $node->end();

        return $node->getDuration();
    }

    /**
     * End a timer
     */
    public function end()
    {
        $node = array_pop($this->cursors);
        $node->end();

        return $node->getDuration();
    }

    public function dump()
    {
        dump($this->render());
    }

    public function dd()
    {
        dd($this->render());
    }

    public function log()
    {
        Log::debug(PHP_EOL . '[Ticktock]' . PHP_EOL . $this->render());
    }

    /**
     * Render the tree
     *
     * @return string
     */
    public function render(): string
    {
        return $this->root->render();
    }
}

<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\Arr;
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
     *
     * @param null $suffix
     * @return void
     */
    public function start($suffix = null)
    {
        $name = $this->getProcessName() . ($suffix ? ' - ' . $suffix : '');

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
     * @param $suffix
     * @return float
     */
    public function capture($suffix = null)
    {
        $line = 'Line ' . $this->getLineNumber();
        $name = $line . ($suffix ? ' - ' . $suffix : '');

        $current_node = end($this->cursors);
        $node = $current_node->addChild($name);
        $node->end();

        return $node->getDuration();
    }

    /**
     * End a timer
     *
     * @return float
     */
    public function end()
    {
        $node = array_pop($this->cursors);
        $node->end();

        return $node->getDuration();
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

    /**
     * Get the method name
     *
     * @return mixed
     */
    protected function getProcessName()
    {
        $backtrace = debug_backtrace();

        $class = Arr::get($backtrace, '3.class', '');
        $function = $backtrace[3]['function'];

        if ($function === 'tts') { // if called from helper function
            $class = $backtrace[4]['class'];
            $function = $backtrace[4]['function'];
        }

        return "$class::$function";
    }

    /**
     * Get the line number
     *
     * @return mixed
     */
    protected function getLineNumber()
    {
        $backtrace = debug_backtrace();

        if ($backtrace[3]['function'] === 'tt') { // if called from helper function
            return $backtrace[3]['line'];
        }

        return $backtrace[2]['line'];
    }
}

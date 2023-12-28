<?php

namespace Vntrungld\LaravelTicktock;

use Illuminate\Support\Facades\Log;

class TickTock
{
    protected $times = [];

    /**
     * Start a timer
     */
    public function start($name)
    {
        $this->times[$name] = microtime(true);
    }

    /**
     * End a timer
     */
    public function end($name)
    {
        $end_time = microtime(true);

        if (! array_key_exists($name, $this->times)) {
            return null;
        }

        $start_time = $this->times[$name];
        $delta_time = $end_time - $start_time;
        $unit = config('ticktock.unit');

        if ($unit === 'ms') {
            $delta_time *= 1000;
        }

        if (config('ticktock.log') === true) {
            $message = config('ticktock.message');
            $message = str_replace([':name', ':time', ':unit'], [$name, $delta_time, $unit], $message);

            Log::debug("[Ticktock] $message");
        }

        unset($this->times[$name]);

        return $delta_time;
    }
}

<?php

namespace Vntrungld\LaravelTicktock\Tests;

use Illuminate\Support\Facades\Log;
use Vntrungld\LaravelTicktock\Facades\TickTock;

class TickTockTest extends TestCase
{
    public function test_a()
    {
        tts('total');
            usleep(10 * 1000);tt('child1');
            usleep(20 * 1000);tt('child2');
            tts('child3');
                usleep(5 * 1000);tt('child3.1');
                usleep(7*1000);tt('child3.2');
                tts('child3.3');
                    usleep(3 * 1000);tt('child3.3.1');
                    usleep(4 * 1000);tt('child3.3.2');
                tte();
                usleep(18 * 1000);tt('child3.4');
                tts('child3.5');
                    usleep(8 * 1000);tt('child3.5.1');
                    usleep(9 * 1000);tt('child3.5.2');
                tte();
            tte();
            tts('child4');
                usleep(10 * 1000);tt('child4.1');
                usleep(15 * 1000);tt('child4.2');
                usleep(20 * 1000);tt('child4.3');
            tte();
        tte();

        ttdd();
    }
}

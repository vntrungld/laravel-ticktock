<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | When disabled, tts()/tt()/tte() and the facade methods are no-ops, so
    | timers accidentally left in the code cost (almost) nothing.
    |
    */

    'enabled' => env('TICKTOCK_ENABLED', true),

];

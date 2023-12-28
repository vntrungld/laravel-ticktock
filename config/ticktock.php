<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Unit of time
    |--------------------------------------------------------------------------
    |
    | Available units: ms, s
    |
    */
    'unit' => env('TICKTOCK_UNIT', 'ms'),

    /*
    |--------------------------------------------------------------------------
    | Add to log
    |--------------------------------------------------------------------------
    |
    | This option controls whether to add to log or not
    |
    */
    'log' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Message format
    |--------------------------------------------------------------------------
    |
    | Default format: Process :name takes :time:unit
    | Eg: [Ticktock] Process foo takes 100ms
    |
    */
    'message' => 'Process :name takes :time:unit',
];

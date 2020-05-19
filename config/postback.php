<?php

return [

    /**
     * Configuration of postbacks drivers
     *
     * To implement a new driver:
     * - create a configuration here
     * - create a new class that implements Postback Inteface
     *
     * Avaiables: ['monetizze']
     *
     */

    'default' => env('POSTBACK_DRIVER', 'monetizze'),

    'drivers' => [
        'monetizze' => [
            'key' =>  env('POSTBACK_KEY')
        ]
    ]
];

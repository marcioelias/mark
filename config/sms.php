<?php

return [

    /**
     * Configuration of sms gateway drivers
     *
     * Avaiables: ['sms_mobile']
     */

    'default' => env('SMS_GATAWEY_DRIVER', 'sms_mobile'),

    'drivers' => [
        'sms_mobile' => [
            'key' =>  env('SMS_GATAWEY_KEY', ''),
            'url' => env('SMS_GATAWEY_URL', '')
        ]
    ]
];

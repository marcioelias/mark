<?php

return [

    /**
     * Configuration of sms gateway drivers
     *
     * Avaiables: ['sms_mobile']
     */

    'default' => env('SMS_GATEWAY_DRIVER', 'sms_mobile'),

    'drivers' => [
        'sms_mobile' => [
            'service' => env('SMS_GATEWAY_SERVICE', ''),
            'key' =>  env('SMS_GATEWAY_KEY', ''),
            'url' => env('SMS_GATEWAY_URL', '')
        ],
        'sandbox' => [
            'url' => env('SMS_GATEWAY_SERVICE', '')
        ]
    ]
];

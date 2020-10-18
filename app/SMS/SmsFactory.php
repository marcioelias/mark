<?php

namespace App\SMS;

use App\Constants\SmsGateways;

class SmsFactory {
    public static function getSmsGateway(string $message = '', string $sendTo = '')
    {
        switch (config('sms.default')) {
            case SmsGateways::SMS_MOBILE:
                return new GatewaySmsMobile($message, $sendTo);
                break;
        }
    }
}

<?php

namespace App\SMS;

use App\Constants\HttpMethods;

class GatewaySmsMobile extends GatewaySms {

    public const ACTION_SEND    = 'enviar';
    public const ACTION_BALANCE = 'consultar';

    public function getUrlParameters(string $endpoint) {
        $params = [
            'hash' => $this->getDriverConfig()['key'],
        ];
        switch ($endpoint) {
            case self::SEND_SMS:
                return array_merge($params, [
                    'acao' => self::ACTION_SEND,
                    'numero' => [
                        $this->sendTo
                    ],
                    'mensagem' => $this->message
                ]);
                break;

            case self::GET_BALANCE:
                return array_merge($params, [
                    'acao' => self::ACTION_BALANCE
                ]);
                break;

            default:
                return $params;
                break;
        }
    }

    public function getRoute(string $route = '') {
        switch ($route) {
            case self::SEND_SMS:
                return '/sms.php';
                break;

            case self::GET_BALANCE:
                return '/saldo.php';
                break;

            default:
                return parent::getRoute($route);
                break;
        }
    }

    // public function getHttpMethod() {
    //     return HttpMethods::GET;
    // }
}

<?php

namespace App\SMS;

use App\Constants\HttpMethods;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GatewaySms {
    public $message;
    public $sendTo;

    public const SEND_SMS = 'SEND_SMS';
    public const GET_BALANCE = 'GET_BALANCE';

    protected $key;

    public function __construct(string $message = '', string $sendTo = '')
    {
        $this->message = $message;
        $this->sendTo = $sendTo;
        $this->key = $this->getDriverConfig()['key'];
    }

    public function send() {
        try {
            $result = $this->callApi($this->getUrl(self::SEND_SMS), $this->getUrlParameters(self::SEND_SMS));

            if ($result['status'] == 'Sucesso') {
                return [
                    'returnMessage' => 'SMS Enviado com sucesso',
                    'successful' => true
                ];
            } else {
                return [
                    'returnMessage' => $result['erro'],
                    'successful' => false
                ];
            }
        } catch (\Exception $e) {
            return [
                'returnMessage' => $e->getMessage(),
                'successful' => false
            ];
            Log::emergency($e->getMessage());
        }
    }

    public function callApi(string $endpoint, array $params) {
        try {
            switch ($this->getHttpMethod()) {
                case HttpMethods::GET:
                    $response = Http::get($endpoint, $params);
                    break;

                case HttpMethods::POST:
                    $response = Http::post($endpoint, $params);
                    break;
            }

            return $response->json();

        } catch (\Exception $e) {
            return response()->json(['status' => 'Erro', 'erro' => 'Erro desconhecido'], 500);
            Log::emergency($e->getMessage());
        }
    }

    public function getBalance() {
        try {
            return $this->callApi($this->getUrl(self::GET_BALANCE), $this->getUrlParameters(self::GET_BALANCE));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDriver() {
        return config('sms.default');
    }

    public function getUrl(string $route = '') {
        return $this->getDriverConfig()['url'].$this->getRoute($route);
    }

    public function getUrlParameters(string $endpoint) {
        return [];
    }

    public function getRoute(string $route = '') {
        return $route;
    }

    public function getHttpMethod() {
        return HttpMethods::POST;
    }

    public function getDriverConfig() {
        return config("sms.drivers.".$this->getDriver());
    }
}

<?php

namespace App\Whatsapp;

use App\Constants\WppInstStatuses;
use App\Models\User\WhatsappInstance;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappEndpoints {
    public const NEW_INSTANCE = '/nova_instancia_whatsapp_api';
    public const GET_QRCODE   = '/getQR';
    public const SEND_TEXT    = '/sendText';
    public const SEND_FILE    = '/sendMediaURL';
    public const LOGOFF       = '/deslogar';
}

class WhatsappIntegration {

    private $apiUrl;
    private $apiHash;
    public $whatsappInstance;

    public function __construct(WhatsappInstance $whatsappInstance)
    {
        $this->whatsappInstance = $whatsappInstance;
        $this->apiUrl = config('whatsapp-api.url', '');
        $this->apiHash = config('whatsapp-api.hash', '');
    }

    public function createInstance() {
        Log::info('port: '.$this->whatsappInstance->port);
        $this->storeInstance($this->getNewInstance());
    }

    private function getNewInstance() {
        return Http::post($this->apiUrl.WhatsappEndpoints::NEW_INSTANCE, [
            'porta' => $this->whatsappInstance->port,
            'cliente' => $this->whatsappInstance->id
        ]);
    }

    private function storeInstance(Response $response) {

        $this->whatsappInstance->url = $response['URL'];
        $this->whatsappInstance->subdomain = $response['PASTA'];
        $this->whatsappInstance->hash = $response['password'];
        $this->whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::DISCONNECTED;

        return $this->whatsappInstance->save();
    }

    public function sendText(string $msg, string $to) {
        try {
            Log::info('url: '.$this->getEndpointURL(WhatsappEndpoints::SEND_TEXT));
            Log::info('to: '.$this->formatPhoneNumber($to));
            Log::info('msg: '.$msg);
            $response = Http::post($this->getEndpointURL(WhatsappEndpoints::SEND_TEXT), [
                'to' => $this->formatPhoneNumber($to),
                'msg' => $msg
            ])->throw();

            Log::debug($response->body());
            return $response->successful();
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    public function sendFile(string $url, string $to) {
        $response = Http::post($this->getEndpointURL(WhatsappEndpoints::SEND_FILE), [
            'to' => $this->formatPhoneNumber($to),
            'url' => $url,
            'cap' => 'emoji'
        ]);

        Log::debug($response->body());
        return $response->successful();
    }

    public function getQrCode() {
        return $this->getEndpointURL(WhatsappEndpoints::GET_QRCODE).'&t='.now()->timestamp;
    }

    public function disconnect() {
        $response = Http::post($this->getEndpointURL(WhatsappEndpoints::LOGOFF), [
            'pasta' => $this->whatsappInstance->subdomain
        ]);

        Log::debug($response);

        return $response->successful();
    }

    private function formatPhoneNumber(string $phoneNumber) {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if ($phoneNumber[0] == '0') {
            $phoneNumber = substr($phoneNumber, 1);
        }

        $number = substr($phoneNumber, -8);
        $areaCode = substr($phoneNumber, 0, 2);
        $countryCode = '55';

        return $countryCode.$areaCode.$number.'@c.us';
    }

    private function getEndpointURL(string $endpoint) {
        return 'https://'.$this->whatsappInstance->url.$endpoint.'?token='.$this->whatsappInstance->hash;
    }
}

<?php

namespace App\Whatsapp;

use App\Constants\WppInstStatuses;
use App\Models\DeactivatedWhatsappInstance;
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
    public const GET_STATUS   = '/status';
    public const RECICLE      = '/reciclar';
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
        if (DeactivatedWhatsappInstance::count()) {
            $this->storeInstance($this->recicleInstance(DeactivatedWhatsappInstance::first()));
        } else {
            $this->storeInstance($this->getNewInstance());
        }
    }

    private function getNewInstance() {
        try {
            $response = Http::post($this->apiUrl.WhatsappEndpoints::NEW_INSTANCE, [
                'porta' => $this->whatsappInstance->port,
                'cliente' => $this->whatsappInstance->id
            ]);

            if ($response->successful()) {
                return $response;
            } else {
                throw new Exception('Erro ao criar instância de Whatsapp. Request: '.$this->apiUrl.WhatsappEndpoints::NEW_INSTANCE.' Response: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    private function storeInstance(Response $response) {
        if ($response) {
            $this->whatsappInstance->url = $response['URL'];
            $this->whatsappInstance->subdomain = $response['PASTA'];
            $this->whatsappInstance->hash = $response['password'];
            $this->whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::DISCONNECTED;

            return $this->whatsappInstance->save();
        } else {
            return false;
        }
    }

    public function sendText(string $msg, string $to) {
        try {
            $response = Http::post($this->getEndpointURL(WhatsappEndpoints::SEND_TEXT), [
                'to' => $this->formatPhoneNumber($to),
                'msg' => $msg
            ])->throw();

            return $response->successful();
        } catch (Exception $e) {
            return false;
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
        $url = $this->apiUrl.WhatsappEndpoints::LOGOFF;
        Log::info('deslogar Url: '.$url);
        $response = Http::post($url, [
            'pasta' => $this->whatsappInstance->subdomain
        ]);

        Log::info($response->body());
        Log::info($response->status());

        return $response->status() == 200;
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

    private function getEndpointURL(string $endpoint, bool $withToken = true) {
        $token = $withToken ? '?token='.$this->whatsappInstance->hash : '';
        return 'https://'.$this->whatsappInstance->url.$endpoint.$token;
    }

    public function recicleInstance(DeactivatedWhatsappInstance $deactivatedWhatsappInstance) {
        try {
            $response = Http::post($this->apiUrl.WhatsappEndpoints::RECICLE, [
                'pasta' => $deactivatedWhatsappInstance->subdomain
            ]);

            if ($response->successful()) {
                $this->whatsappInstance->port = $deactivatedWhatsappInstance->port;
                $deactivatedWhatsappInstance->delete();
                return $response;
            } else {
                throw new Exception('Erro ao criar instância de Whatsapp. Request: '.$this->apiUrl.WhatsappEndpoints::NEW_INSTANCE.' Response: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            throw $e;
        }
    }

    public function getStatus() {
        try {
            $url = $this->getEndpointURL(WhatsappEndpoints::GET_STATUS, false)."/".$this->whatsappInstance->hash;
            Log::info('URL Status: '.$url);
            return Http::get($url);
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    public function updateInstanceStatus() {
        try {
            $this->whatsappInstance->whatsapp_instance_status_id = ((string) $this->getStatus() === (string) 'offline') ? WppInstStatuses::DISCONNECTED : WppInstStatuses::CONNECTED;
            $this->whatsappInstance->save();
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
        }
    }
}

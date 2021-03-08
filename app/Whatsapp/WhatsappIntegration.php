<?php

namespace App\Whatsapp;

use App\Constants\WppInstStatuses;
use App\Models\DeactivatedWhatsappInstance;
use App\Models\User\WhatsappInstance;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappEndpoints {
    public const NEW_INSTANCE = '/nova_instancia_whatsapp_api';
    public const GET_QRCODE   = '/getQR';
    public const SEND_TEXT    = '/sendText';
    public const SEND_FILE    = '/sendMediaURL';
    public const SEND_PDFURL  = '/sendPDFURL';
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
        if (config('app.debug', false)) return;
        Log::info('ops...');
        try {
            if (DeactivatedWhatsappInstance::count()) {
                $this->storeInstance($this->recicleInstance(DeactivatedWhatsappInstance::first()));
            } else {
                $this->storeInstance($this->getNewInstance());
            }
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    private function getNewInstance() {
        try {
            $response = Http::timeout(30)->post($this->apiUrl.WhatsappEndpoints::NEW_INSTANCE, [
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
        try {
            if ($response) {
                $this->whatsappInstance->url = $response['URL'];
                $this->whatsappInstance->subdomain = $response['PASTA'];
                $this->whatsappInstance->hash = $response['password'];
                $this->whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::DISCONNECTED;

                return $this->whatsappInstance->save();
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function sendText(string $msg, string $to) {
        try {
            $endpoint = $this->getEndpointURL(WhatsappEndpoints::SEND_TEXT);
            if ($endpoint) {
                $response = Http::timeout(30)->post($endpoint, [
                    'to' => $this->formatPhoneNumber($to),
                    'msg' => $msg
                ]);

                if ($response->successful()) {
                    return [
                        'returnMessage' => 'Whatsapp Enviado com sucesso',
                        'successful' => true
                    ];
                } else {
                    if ($response->status() == 401) {
                        return [
                            'returnMessage' => 'Instância desconectada, por favor, re-leia o QRCode.',
                            'successful' => false
                        ];
                    } else {
                        return [
                            'returnMessage' => 'Ocorreu um erro desconhecido. Whatsapp não pode ser enviado.',
                            'successful' => false
                        ];
                    }
                }
            } else {
                return [
                    'returnMessage' => 'Instância não configurada!',
                    'successful' => false
                ];
            }

        } catch (Exception $e) {
            return [
                'returnMessage' => $e->getMessage(),
                'successful' => false
            ];
            Log::emergency($e->getMessage());
        }
    }

    public function sendFile(string $url, string $to) {
        $endpoint = $this->getEndpointURL(WhatsappEndpoints::SEND_FILE);
        if ($endpoint) {
            $response = Http::timeout(30)->post($endpoint, [
                'to' => $this->formatPhoneNumber($to),
                'url' => $url
            ]);

            return $response->successful();
        } else {
            return false;
        }
    }

    public function sendPDF(string $url, string $to) {
        $endpoint = $this->getEndpointURL(WhatsappEndpoints::SEND_PDFURL);
        if ($endpoint) {
            $response = Http::timeout(30)->post($endpoint, [
                'to' => $this->formatPhoneNumber($to),
                'url' => $url
            ]);

            return $response->successful();
        } else {
            return false;
        }
    }

    public function getQrCode() {
        $endpoint = $this->getEndpointURL(WhatsappEndpoints::GET_QRCODE);
        if ($endpoint) {
            return $endpoint.'&t='.now()->timestamp;
        } else {
            return false;
        }
    }

    public function disconnect() {
        if ($this->whatsappInstance->url) {
            $url = $this->apiUrl.WhatsappEndpoints::LOGOFF;
            $response = Http::timeout(30)->post($url, [
                'pasta' => $this->whatsappInstance->subdomain
            ]);

            return $response->status() == 200;
        }
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
        if ($this->whatsappInstance->url) {
            $token = $withToken ? '?token='.$this->whatsappInstance->hash : '';
            return 'https://'.$this->whatsappInstance->url.$endpoint.$token;
        } else {
            return false;
        }
    }

    public function recicleInstance(DeactivatedWhatsappInstance $deactivatedWhatsappInstance) {
        try {
            $response = Http::timeout(30)->post($this->apiUrl.WhatsappEndpoints::RECICLE, [
                'pasta' => $deactivatedWhatsappInstance->subdomain
            ]);

            Log::info('Reciclando instância de Whatsapp ID: '.$deactivatedWhatsappInstance->subdomain);

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
            $endpoint = $this->getEndpointURL(WhatsappEndpoints::GET_STATUS, false);
            if ($endpoint) {
                $url = $endpoint."/".$this->whatsappInstance->hash;
                return Http::timeout(30)->get($url);
            }
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    public function updateInstanceStatus() {
        try {
            if ($this->whatsappInstance->url) {
                $this->whatsappInstance->whatsapp_instance_status_id = ((string) $this->getStatus() === (string) 'offline') ? WppInstStatuses::DISCONNECTED : WppInstStatuses::CONNECTED;
                $this->whatsappInstance->save();
            }
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
        }
    }
}

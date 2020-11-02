<?php

namespace App\Whatsapp;

use App\Models\User\WhatsappInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $response = Http::post($this->apiUrl.'/nova_instancia_whatsapp_api', [
            'clienteid' => $this->whatsappInstance->id,
            'port' => $this->whatsappInstance->port
        ]);
    }

}




/* Log::info('url: '.$this->apiUrl.'/nova_instancia_whatsapp_api');
        Log::info('data: ');
        Log::debug([
            'clienteid' => $this->whatsappInstance->id,
            'port' => $this->whatsappInstance->port
        ]);
        Log::info('response');
        Log::debug($response); */

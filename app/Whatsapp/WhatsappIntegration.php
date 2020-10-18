<?php

namespace App\Whatsapp;

use App\Models\User\WhatsappInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
        Http::post($this->apiUrl, [
            'clienteid' => Auth::user()->id,
            'senha' => $this->apiHash,
            'port' => $this->whatsappInstance->port
        ]);
    }

}

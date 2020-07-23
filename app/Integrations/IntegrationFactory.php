<?php

namespace App\Integrations;

use App\Models\User\PlataformConfig;
use Illuminate\Http\Request;

class IntegrationFactory {
    public static function getIntegration(Request $request, PlataformConfig $plataformConfig) {
        switch ($plataformConfig->plataform->plataform_name) {
            case 'Eduzz':
                return new EduzzIntegration($request, $plataformConfig);
                break;

            case 'Hotmart':
                return new HotmartIntegration($request, $plataformConfig);
                break;

            case 'Monetizze':
                return new MonetizzeIntegration($request, $plataformConfig);
                break;
        }
    }
}

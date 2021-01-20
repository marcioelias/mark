<?php

namespace App\Integrations;

use App\Constants\IntegratedPlataforms;
use App\Models\User\PlataformConfig;
use Illuminate\Http\Request;

class IntegrationFactory {
    public static function getIntegration(Request $request, PlataformConfig $plataformConfig) {
        switch ($plataformConfig->plataform_id) {
            case IntegratedPlataforms::EDUZZ:
                return new EduzzIntegration($request, $plataformConfig);
                break;

            case IntegratedPlataforms::HOTMART:
                return new HotmartIntegration($request, $plataformConfig);
                break;

            case IntegratedPlataforms::MONETIZZE:
                return new MonetizzeIntegration($request, $plataformConfig);
                break;

            case IntegratedPlataforms::PERFECTPAY:
                return new PerfectPayIntegration($request, $plataformConfig);
                break;

            case IntegratedPlataforms::TICTO:
                return new TictoIntegration($request, $plataformConfig);
                break;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Plataform;
use App\Models\User\PlataformConfig;
use Illuminate\Http\Request;

class WebhookCallController extends Controller
{
    public function receiveUserWebhook(PlataformConfig $plataformConfig) {
        return response()->json($plataformConfig);
    }
}

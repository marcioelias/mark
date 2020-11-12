<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPayment;
use App\MercadoPago\MercadoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookMercadoPagoController extends Controller
{
    public function receive(Request $request) {
        if ((string) $request->type === 'payment') {
            Log::debug($request->all());
            ProcessPayment::dispatch($request->data_id);
        }

        return response()->json(['status' => 'Ok']);
    }
}

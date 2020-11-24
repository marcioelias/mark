<?php

namespace App\SMS;

use Exception;
use Illuminate\Support\Facades\Log;

class GatewaySandbox extends GatewaySandbox {
    public function getBalance() {
        try {
            return response()->json(['balance' => 999999]);
        } catch (Exception $e) {
            return false;
            Log::emergency($e->getMessage());
        }
    }

    public function getUrlParameters(string $endpoint) {
        return ['data' => $this->message];
    }

    // public function getRoute(string $route = '') {
    //     //return $route;
    // }

    //Http::post('https://api.dev.test/api/sms', ['data' => $msg]);
}

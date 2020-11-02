<?php

namespace App\MercadoPago;

use App\Models\SmsPackage;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

class MercadoPago {

    private $token;

    public function __construct()
    {
        $this->token = env('MERCADO_PAGO_ACCESS_TOKEN');
        SDK::setAccessToken($this->token);
    }

    public function sellSMSPackage(SmsPackage $smsPackage) {
        $item = new Item();
        $item->title = $smsPackage->sms_package_name;
        $item->quantity = 1;
        $item->unit_price = $smsPackage->package_value;
        return $this->getPreferenceItem($item);
    }

    public function getPreferenceItem(Item $item) {
        $preference = new Preference();
        $preference->items = array($item);
        $preference->save();
        return $preference;
    }
}

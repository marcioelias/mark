<?php

namespace App\MercadoPago;

use App\Models\SmsPackage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Payment;
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
        $item->id = $smsPackage->id;
        $item->title = $smsPackage->sms_package_name;
        $item->description = $smsPackage->sms_package_description;
        $item->category_id = $smsPackage->category;
        $item->quantity = 1;
        $item->currency_id = 'BRL';
        $item->unit_price = $smsPackage->package_value;
        return $this->getPreferenceItem($item);
    }

    public function getPreferenceItem(Item $item) {
        Log::debug($item);
        $preference = new Preference();
        $preference->items = array($item);
        $preference->back_urls = $this->getBackUrls();
        $preference->payer = $this->getPayer();
        $preference->external_reference = json_encode(['product_id' => $item->id, 'user_id' => Auth::user()->id]);
        $preference->notification_url = config('app.url').'/webhook/system/mercadopago';
        $preference->save();
        return $preference;
    }

    public function getBackUrls() {
        return array(
            'success' => route('sms.buy.response', ['response' => 'success']),
            'failure' => route('sms.buy.response', ['response' => 'failure']),
            'pending' => route('sms.buy.response', ['response' => 'pending']),
        );
    }

    public function getPayer() {
        $user = Auth::user();
        return new Payer([
            'name' => $user->name,
            'surname' => $user->last_name,
            'email' => $user->email,
            'date_created' => $user->created_at,
            'phone' => $this->getPhoneNumber($user->phone_number),
            'identification' => [
                "type" => $user->doc_type,
                "number" => preg_replace('/\D/', '', $user->doc_number)
            ],
            'address' => [
                "street_name" => $user->street_name,
                "street_number" => $user->street_number,
                "zip_code" => preg_replace('/\D/', '', $user->zip_code)
            ]
        ]);
    }

    public function getPhoneNumber(String $phoneNumber) {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if ($phoneNumber[0] == '0') {
            $phoneNumber = substr($phoneNumber, 1);
        }

        $number = substr($phoneNumber, -8);
        $areaCode = substr($phoneNumber, 0, 2);

        return [
            "area_code" => $areaCode,
            "number" => $number
        ];
    }

    public function getPaymentInfo(String $paymentID) {
        try {
            return Payment::find_by_id($paymentID);
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
        }
    }
}

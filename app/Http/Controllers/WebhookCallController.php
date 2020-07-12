<?php

namespace App\Http\Controllers;

use App\Models\Plataform;
use App\Models\User\Customer;
use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use App\Models\User\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookCallController extends Controller
{

    private $user = null;

    public function receiveUserWebhook(Request $request, PlataformConfig $plataformConfig) {
        $this->user = $plataformConfig->user;

        switch ($plataformConfig->plataform->plataform_name) {
            case 'Eduzz':
                return response()->json($this->integracaoEduzz($request));
                break;

            case 'Hotmart':
                return response()->json($this->integracaoHotmart($request));
                break;

            case 'Monetizze':
                return response()->json($this->integracaoMonetizze($request));
                break;
        }
        return response()->json($plataformConfig);
    }

    private function integracaoEduzz(Request $request) {

    }

    private function integracaoHotmart(Request $request) {

    }

    private function integracaoMonetizze(Request $request) {
        //Log::debug($request->all());
        DB::beginTransaction();
        try {
            $customer = $this->getCustomer(new Customer([
                'customer_name' => $request['comprador']['nome'],
                'customer_phone_number' => $request['comprador']['telefone'],
                'customer_email' => $request['comprador']['email']
            ]));

            $transaction = new Transaction();
            $transaction->user_id = $this->user->id;
            $transaction->product_id = $this->getProductByCode($request['produto']['codigo'])->id;
            $transaction->customer_id = $customer->id;
            $transaction->transaction_code = $request['venda']['codigo'];
            $transaction->billet_url = $request['venda']['linkBoleto'];
            $transaction->billet_barcode = $request['venda']['linha_digitavel'];
            $transaction->transaction_payload = $request['json'];

            $transaction->save();

            DB::commit();

            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency($e);
            return [
                'status' => 'error',
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    private function store(Transaction $transaction) {

    }

    private function getProductByCode($code) {
        Log::debug(Product::where('plataform_code', $code)->first());
        return Product::where('plataform_code', $code)->first();
    }

    private function getCustomer(Customer $customer) {
        return Customer::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'customer_email' => $customer->customer_email
            ],
            [
                'customer_name' => $customer->customer_name,
                'customer_phone_number' => $customer->customer_phone_number
            ]
        );
    }
}

<?php

namespace App\Listeners;

use App\Constants\TransactionTypes;
use App\Events\OnMercadoPagoPaymentReceived;
use App\Models\SmsPackage;
use App\Models\User;
use App\Models\User\SmsUserTransaction;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Payment;

class DoOnMercadoPagoPaymentReceived
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnMercadoPagoPaymentReceived  $event
     * @return void
     */
    public function handle(OnMercadoPagoPaymentReceived $event)
    {
        DB::beginTransaction();
        try {
            /* processa a saida do controle de estoque geral */
            $this->generalStockOut($event->payment);

            /* processa a entrada no controle de estoque do usuário */
            $this->userStockIn($event->payment);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::emergency($e->getMessage());
        }
    }

    private function generalStockOut(Payment $payment) {
        //
    }

    private function userStockIn(Payment $payment) {
        try {
            $data = json_decode($payment->external_reference, true);
            $smsPackage = SmsPackage::find($data['product_id']);
            SmsUserTransaction::create([
                'user_id' => $data['user_id'],
                'quantity' => $smsPackage->sms_amount,
                'transaction_type_id' => TransactionTypes::IN
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}

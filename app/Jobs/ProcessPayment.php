<?php

namespace App\Jobs;

use App\Events\OnMercadoPagoPaymentReceived;
use App\MercadoPago\MercadoPago;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $paymentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mercadoPago = new MercadoPago();
        $payment = $mercadoPago->getPaymentInfo($this->paymentId);

        event(new OnMercadoPagoPaymentReceived($payment));
    }
}

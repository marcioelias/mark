<?php

use App\Constants\PaymentTypes;
use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentTypes = [
            [
                'id' => PaymentTypes::BOLETO_BANCARIO,
                'payment_type' => 'Boleto Bancário'
            ],
            [
                'id' => PaymentTypes::CARTAO_CREDITO,
                'payment_type' => 'Cartão de Crédito'
            ],
            [
                'id' => PaymentTypes::CARTAO_DEBIDO,
                'payment_type' => 'Cartão de Débito'
            ],
            [
                'id' => PaymentTypes::PAYPAL,
                'payment_type' => 'Paypal'
            ],
            [
                'id' => PaymentTypes::OUTROS,
                'payment_type' => 'Outros'
            ]
        ];

        foreach ($paymentTypes as $paymentType) {
            (new PaymentType($paymentType))->save();
        }
    }
}

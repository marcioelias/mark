<?php

use App\Constants\PostbackEventType as ConstantPostbackEventType;
use App\Models\PostbackEventType;
use Illuminate\Database\Seeder;

class PostbackEventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventTypes = [
            [
                'id' => ConstantPostbackEventType::APPROVED,
                'postback_event_type' => 'Aprovado',
            ],
            [
                'id' => ConstantPostbackEventType::CANCELED,
                'postback_event_type' => 'Cancelado',
            ],
            [
                'id' => ConstantPostbackEventType::BILLET_PRINTED,
                'postback_event_type' => 'Boleto Impresso',
            ],
            [
                'id' => ConstantPostbackEventType::REFUNDED,
                'postback_event_type' => 'Devolvido',
            ],
            [
                'id' => ConstantPostbackEventType::DISPUTE,
                'postback_event_type' => 'Disputa',
            ],
            [
                'id' => ConstantPostbackEventType::WAITING_PAYMENT,
                'postback_event_type' => 'Aguardando Pagamento',
            ]
        ];

        foreach ($eventTypes as $eventType) {
            PostbackEventType::create($eventType);
        }
    }
}

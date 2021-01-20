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
                ['id' => ConstantPostbackEventType::APPROVED],
                ['postback_event_type' => 'Compra Efetuada'],
            ],
            [
                ['id' => ConstantPostbackEventType::CANCELED],
                ['postback_event_type' => 'Vencido'],
            ],
            [
                ['id' => ConstantPostbackEventType::BILLET_PRINTED],
                ['postback_event_type' => 'Boleto Impresso'],
            ],
            [
                ['id' => ConstantPostbackEventType::REFUNDED],
                ['postback_event_type' => 'Reembolsado'],
            ],
            [
                ['id' => ConstantPostbackEventType::DISPUTE],
                ['postback_event_type' => 'Aguardando Reembolso'],
            ],
        ];

        foreach ($eventTypes as $eventType) {
            PostbackEventType::updateOrCreate($eventType[0], $eventType[1]);
        }
    }
}

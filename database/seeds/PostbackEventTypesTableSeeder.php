<?php

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
                'id' => '6106e1cc-59c3-4532-94d9-be4600cb2f19',
                'postback_event_type' => 'Abandonou checkout',
            ],
            [
                'id' => '8189606f-3d6e-470d-9ee8-7f94a61d1327',
                'postback_event_type' => 'Imprimiu Boleto',
            ],
            [
                'id' => '32d56274-3131-41f4-8a7a-a981a4264572',
                'postback_event_type' => 'Boleto Vencendo',
            ],
            [
                'id' => '6bc14a06-4cbf-4955-a2df-4215dcc51ba5',
                'postback_event_type' => 'Boleto Venceu',
            ],
            [
                'id' => '85b0f9e0-4d3f-4c08-ad29-c65015a01b1d',
                'postback_event_type' => 'Compra Finalizada',
            ],
            [
                'id' => 'da6bd873-b04a-4f6c-bf6d-47757db96df6',
                'postback_event_type' => 'Compra Cancelada',
            ]
        ];

        foreach ($eventTypes as $eventType) {
            PostbackEventType::create($eventType);
        }
    }
}

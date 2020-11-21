<?php

use App\Constants\LeadStatuses;
use App\Models\User\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leadStatuses = [
            [
                'id' => LeadStatuses::APPROVED,
                'status' => 'Compra Efetuada'
            ],
            [
                'id' => LeadStatuses::CANCELED,
                'status' => 'Vencido'
            ],
            [
                'id' => LeadStatuses::BILLET_PRINTED,
                'status' => 'Boleto Impresso'
            ],
            [
                'id' => LeadStatuses::REFUNDED,
                'status' => 'Reembolsado'
            ],
            [
                'id' => LeadStatuses::DISPUTE,
                'status' => 'Aguardando Reembolso'
            ],
        ];

        foreach($leadStatuses as $leadStatus) {
            LeadStatus::create($leadStatus);
        }
    }
}

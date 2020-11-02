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
                'status' => 'Aprovado'
            ],
            [
                'id' => LeadStatuses::CANCELED,
                'status' => 'Cancelado'
            ],
            [
                'id' => LeadStatuses::BILLET_PRINTED,
                'status' => 'Boleto Impresso'
            ],
            [
                'id' => LeadStatuses::REFUNDED,
                'status' => 'Devolvido'
            ],
            [
                'id' => LeadStatuses::DISPUTE,
                'status' => 'Disputa'
            ],
            [
                'id' => LeadStatuses::WAITING_PAYMENT,
                'status' => 'Aguardando Pagamento'
            ],

        ];

        foreach($leadStatuses as $leadStatus) {
            LeadStatus::create($leadStatus);
        }
    }
}

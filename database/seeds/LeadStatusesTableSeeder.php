<?php

use App\Constants\DefaultLeadStatuses;
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
                'id' => DefaultLeadStatuses::ACTIVE,
                'status' => 'Ativo'
            ],
            [
                'id' => DefaultLeadStatuses::REMARKETING,
                'status' => 'Remarketing'
            ],
            [
                'id' => DefaultLeadStatuses::INACTIVE,
                'status' => 'Inativo'
            ]
        ];

        foreach($leadStatuses as $leadStatus) {
            LeadStatus::create($leadStatus);
        }
    }
}

<?php

use App\Constants\MarketingActionStatuses;
use App\Models\MarketingActionStatus;
use Illuminate\Database\Seeder;

class MarketingActionStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marketingActionStatuses = [
            [
                'id' => MarketingActionStatuses::PENDING,
                'marketing_action_status' => 'Pendente'
            ],
            [
                'id' => MarketingActionStatuses::RUNNING,
                'marketing_action_status' => 'Executando'
            ],
            [
                'id' => MarketingActionStatuses::DONE,
                'marketing_action_status' => 'Concluída'
            ],
        ];

        foreach ($marketingActionStatuses as $marketingActionStatus) {
            MarketingActionStatus::create($marketingActionStatus);
        }
    }
}

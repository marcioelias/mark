<?php

use App\Models\LeadStatus;
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
            ['id' => 'b48461c4-d40b-4f40-9778-235b6a0402ff', 'status' => 'Abandono de Checkout'],
            ['id' => 'b847c45a-ec3b-4793-b645-00fb1dcdf833', 'status' => 'Em Aberto'],
            ['id' => '408ee6a6-4387-4915-b794-be9fb510527c', 'status' => 'Vencendo'],
            ['id' => '9a0752ea-8bf5-422c-a8a6-b994d26f433f', 'status' => 'Vencido'],
            ['id' => '067286c4-887c-45d5-a115-0b2848b40e90', 'status' => 'Finalizado'],
            ['id' => 'ff401095-37f5-4809-a346-2ef512c2f66c', 'status' => 'Cancelado']
        ];

        foreach($leadStatuses as $leadStatus) {
            LeadStatus::create($leadStatus);
        }
    }
}

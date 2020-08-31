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
            ['status' => 'Ativo'],
            ['status' => 'Remarketing'],
            ['status' => 'Inativo'],
        ];

        foreach($leadStatuses as $leadStatus) {
            LeadStatus::create($leadStatus);
        }
    }
}

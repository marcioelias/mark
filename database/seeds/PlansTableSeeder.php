<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'marketplace_code' => 'd166bc9efec4b99953fa17aa5912d648',
            'plan_name' => 'Plano 1000',
            'num_postbacks' => 1000,
            'plan_value' => 129.90,
            'created_at' => now()
        ]);
        factory(Plan::class, 5)->create();
    }
}

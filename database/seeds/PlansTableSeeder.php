<?php

use App\Models\Feature;
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
        $plan = new Plan([
            'marketplace_code' => 'd166bc9efec4b99953fa17aa5912d648',
            'plan_name' => 'Plano 1000',
            'plan_cycle_days' => 30,
            'plan_value' => 129.90,
        ]);
        $plan->save();

        foreach (Feature::all() as $feature) {
            $plan->features()->attach($feature->id, ['enabled' => true, 'limit' => 1000]);
        }


        //factory(Plan::class, 5)->create();
    }
}

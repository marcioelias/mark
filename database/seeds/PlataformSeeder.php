<?php

use App\Constants\IntegratedPlataforms;
use App\Models\Plataform;
use Illuminate\Database\Seeder;

class PlataformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plataforms = [
            [
                'id' => IntegratedPlataforms::EDUZZ,
                'plataform_name' => 'Eduzz'
            ],
            [
                'id' => IntegratedPlataforms::HOTMART,
                'plataform_name' => 'Hotmart'
            ],
            [
                'id' => IntegratedPlataforms::MONETIZZE,
                'plataform_name' => 'Monetizze'
            ],
            [
                'id' => IntegratedPlataforms::PERFECTPAY,
                'plataform_name' => 'PerfectPay'
            ],
        ];

        foreach ($plataforms as $plataform) {
            (new Plataform($plataform))->save();
        }
    }
}

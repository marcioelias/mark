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
                ['id' => IntegratedPlataforms::EDUZZ],
                ['plataform_name' => 'Eduzz']
            ],
            [
                ['id' => IntegratedPlataforms::HOTMART],
                ['plataform_name' => 'Hotmart']
            ],
            [
                ['id' => IntegratedPlataforms::MONETIZZE],
                ['plataform_name' => 'Monetizze']
            ],
            [
                ['id' => IntegratedPlataforms::PERFECTPAY],
                ['plataform_name' => 'PerfectPay']
            ],
            [
                ['id' => IntegratedPlataforms::TICTO],
                ['plataform_name' => 'Ticto']
            ]
        ];

        foreach ($plataforms as $plataform) {
            Plataform::updateOrCreate($plataform[0], $plataform[1]);
        }
    }
}

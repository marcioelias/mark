<?php

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
            ['plataform_name' => 'Eduzz'],
            ['plataform_name' => 'Hotmart'],
            ['plataform_name' => 'Monetizze'],
        ];

        foreach ($plataforms as $plataform) {
            (new Plataform($plataform))->save();
        }
    }
}

<?php

use App\Models\Variable;
use Illuminate\Database\Seeder;

class VariablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variables = [
            [
                ['variable' => '{ nome_cliente }'],
                ['description' => 'Nome do Cliente']
            ],
            [
                ['variable' => '{ primeiro_nome }'],
                ['description' => 'Primeiro Nome do Cliente']
            ],
            [
                ['variable' => '{ telefone_cliente }'],
                ['description' => 'Telefone do Cliente']
            ],
            [
                ['variable' => '{ email_cliente }'],
                ['description' => 'E-mail do Cliente']
            ],
            [
                ['variable' => '{ url_boleto }'],
                ['description' => 'Url do Boleto']
            ],
            [
                ['variable' => '{ linha_digitavel }'],
                ['description' => 'Linha Digitável do Boleto']
            ]
        ];
        
        foreach ($variables as $variable) {
            Variable::updateOrCreate($variable[0], $variable[1]);
        }
    }
}

<?php

use App\Models\ActionType;
use Illuminate\Database\Seeder;

class ActionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actionTypes = [
            [
                'id' => '3b9ea3c4-6973-4523-b793-f4ba530597e5',
                'action_type_name' => 'email',
                'action_type_description' => 'Enviar E-mail',
            ],
            [
                'id' => '389ce5cd-14fc-4d88-a810-9c8d15d74895',
                'action_type_name' => 'sms',
                'action_type_description' => 'Enviar SMS',
            ],
            [
                'id' => '1ef0d03c-9f90-408a-b14b-ccee49a2dd6d',
                'action_type_name' => 'whatsapp',
                'action_type_description' => 'Enviar WhatsApp',
            ],
            [
                'id' => 'a0ff06d6-b48e-4ab4-b478-8c2e1b5b20d7',
                'action_type_name' => 'funnel',
                'action_type_description' => 'Mover para o Funil',
            ]
        ];

        foreach ($actionTypes as $actionType) {
            $at = new ActionType($actionType);
            $at->save();
        }
    }
}

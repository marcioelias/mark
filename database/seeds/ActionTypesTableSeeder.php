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
                'action_type_name' => 'email',
                'action_type_description' => 'E-mail',
            ],
            [
                'action_type_name' => 'sms',
                'action_type_description' => 'SMS',
            ],
            [
                'action_type_name' => 'whatsapp',
                'action_type_description' => 'WhatsApp',
            ]
        ];

        foreach ($actionTypes as $actionType) {
            $at = new ActionType($actionType);
            $at->save();
        }
    }
}

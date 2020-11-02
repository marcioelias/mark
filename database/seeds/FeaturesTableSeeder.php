<?php

use App\Constants\ActionTypes;
use App\Constants\FeatureTypes;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = [
            [
                'id' => FeatureTypes::POSTBACKS,
                'feature' => 'Postbacks',
                'action_type_id' => null,
                'order' => 1
            ],
            [
                'id' => FeatureTypes::LEADS,
                'feature' => 'Leads',
                'action_type_id' => null,
                'order' => 2
            ],
            [
                'id' => FeatureTypes::EMAILS,
                'feature' => 'E-mails',
                'action_type_id' => ActionTypes::EMAIL,
                'order' => 3
            ],
            [
                'id' => FeatureTypes::SMS,
                'feature' => 'SMS',
                'action_type_id' => ActionTypes::SMS,
                'order' => 4
            ],
            [
                'id' => FeatureTypes::WHATSAPP,
                'feature' => 'Whatsapp',
                'action_type_id' => ActionTypes::WHATSAPP,
                'order' => 5
            ]
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}

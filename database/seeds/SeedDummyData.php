<?php

use App\Constants\ActionTypes;
use App\Constants\LeadStatus;
use App\Models\Plataform;
use App\Models\User;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use App\Models\User\Tag;
use App\Models\User\TagRule;
use Illuminate\Database\Seeder;

class SeedDummyData extends Seeder
{
    private const CONFIG_EDUZZ_ID = '1bdb8a42-5002-415c-be42-411ea64002bf';
    private const CONFIG_HOTMART_ID = '4b227284-6b64-4da9-b013-7ab18f9504a5';
    private const CONFIG_MONETIZZE_ID = 'a95a917a-736c-4dec-995f-cd816519d957';
    private const TAG_ABERTO_ID = 'af48e83f-f409-4814-ad7c-c33daea8a5c8';
    private const TAG_PAGO_ID = '5db3eca4-2590-4034-99bf-1e645249c12d';
    private const PRODUCT_EDUZZ_ID = '2c8ee202-f35c-409b-bed6-86b890a4c4f3';
    private const PRODUCT_HOTMART_ID = '4e50189c-332d-4561-b611-e27776cbfca5';
    private const PRODUCT_MONETIZZE_ID = '753d9cc4-0c8a-4965-be00-65577229fe96';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'user@app.com')->first();

        /* PLATAFORM CONFIG */
        $plataforms = Plataform::all();
        foreach($plataforms as $plataform) {
            switch ($plataform->plataform_name) {
                case 'Eduzz':
                    $plataformConfig = new PlataformConfig([
                        'id' => self::CONFIG_EDUZZ_ID,
                        'user_id' => $user->id,
                        'plataform_id' => $plataform->id,
                        'plataform_key' => '123123123',
                        'active' => true
                    ]);
                break;
                case 'Hotmart':
                    $plataformConfig = new PlataformConfig([
                        'id' => self::CONFIG_HOTMART_ID,
                        'user_id' => $user->id,
                        'plataform_id' => $plataform->id,
                        'plataform_key' => '123123123',
                        'active' => true
                    ]);
                break;
                case 'Monetizze':
                    $plataformConfig = new PlataformConfig([
                        'id' => self::CONFIG_MONETIZZE_ID,
                        'user_id' => $user->id,
                        'plataform_id' => $plataform->id,
                        'plataform_key' => '123123123',
                        'active' => true
                    ]);
                break;
            }

            $plataformConfig->save();
        }

        /* PRODUCTS */
        $products = [
            [
                'id' => self::PRODUCT_EDUZZ_ID,
                'user_id' => $user->id,
                'plataform_config_id' => self::CONFIG_EDUZZ_ID,
                'plataform_code' => '123123',
                'product_name' => 'Produto Eduzz',
                'product_price' => 199.99,
                'active' => true
            ],
            [
                'id' => self::PRODUCT_HOTMART_ID,
                'user_id' => $user->id,
                'plataform_config_id' => self::CONFIG_HOTMART_ID,
                'plataform_code' => '123123',
                'product_name' => 'Produto Hotmart',
                'product_price' => 159.99,
                'active' => true
            ],
            [
                'id' => self::PRODUCT_MONETIZZE_ID,
                'user_id' => $user->id,
                'plataform_config_id' => self::CONFIG_MONETIZZE_ID,
                'plataform_code' => '123123',
                'product_name' => 'Produto Monetizze',
                'product_price' => 99.99,
                'active' => true
            ],
        ];
        /* foreach($products as $product) {
            $prod = new Product($product);
            $prod->save();
        } */

        /* FUNNELS */
        // $funnels = [
        //     [
        //       "user_id" => $user->id,
        //       "product_id" => self::PRODUCT_EDUZZ_ID,
        //       "tag_id" => "af48e83f-f409-4814-ad7c-c33daea8a5c8",
        //       "active" => 1,
        //     ],
        // ];

        // foreach ($funnels as $funnel) {
        //     $f = new Funnel($funnel);
        //     $f->save();

        //     /* FUNNEL STEPS */
        //     $this->createFunnelSteps($f, $user);
        // }
    }

    public function createFunnelSteps(Funnel $funnel, User $user) {
        $funnelSteps = [
            [
                "user_id" => $user->id,
                "funnel_id" => $funnel->id,
                "funnel_step_sequence" => 1,
                "funnel_step_description" => "Passo 1",
                "new_tag_id" => null,
                "delay_days" => 0,
                "delay_hours" => 0,
            ],
            [
                "user_id" => $user->id,
                "funnel_id" => $funnel->id,
                "funnel_step_sequence" => 2,
                "funnel_step_description" => "Passo 2",
                "new_tag_id" => null,
                "delay_days" => 0,
                "delay_hours" => 1,
            ],
            [
                "user_id" => $user->id,
                "funnel_id" => $funnel->id,
                "funnel_step_sequence" => 3,
                "funnel_step_description" => "Passo 3",
                "new_tag_id" => null,
                "delay_days" => 0,
                "delay_hours" => 1,
            ],
        ];

        $i = 1;
        foreach ($funnelSteps as $funnelStep) {
            $fs = new FunnelStep($funnelStep);
            $fs->save();

            $funnelStepAction = new FunnelStepAction([
                "user_id" => $user->id,
                "action_type_id" => ActionTypes::SMS,
                "action_sequence" => 1,
                "action_description" => "Enviar SMS",
                "action_data" => json_encode(["data" => "$i passo { nome_cliente }", "options" => []]),
            ]);

            $fs->actions()->save($funnelStepAction);
            $i++;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User\Postback;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;

class PostbackController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'transaction_code' => 'Transação',
            'plataform_name' => 'Plataforma',
            'product_name' => 'Produto',
            'customer_name' => 'Cliente',
            'postback_event_type' => 'Evento',
            'created_at' => ['label' => 'Data', 'type' => 'datetime']
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Integrações'
            ],
            [
                'name' => "Postbacks"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'created_at',
            'order_type' => 'DESC'
        ]);

        if ($request->searchField) {
            $postbacks = Postback::select('postbacks.*', 'plataforms.plataform_name', 'products.product_name', 'customers.customer_name', 'postback_event_types.postback_event_type')
                            ->join('products', 'products.id', 'postbacks.product_id')
                            ->join('plataform_configs', 'plataform_configs.id', 'products.plataform_config_id')
                            ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                            ->join('customers', 'customers.id', 'postbacks.customer_id')
                            ->join('postback_event_types', 'postback_event_types.id', 'postbacks.postback_event_type_id')
                            ->where('postbacks.transaction_code', 'like', "%$request->searchField%")
                            ->orWhere('plataforms.plataform_name', 'like', "%$request->searchField%")
                            ->orWhere('products.product_name', 'like', "%$request->searchField%")
                            ->orWhere('customers.customer_name', 'like', "%$request->searchField%")
                            ->orWhere('postback_event_types.postback_event_type', 'like', "$request->searchField")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $postbacks = Postback::select('postbacks.*', 'plataforms.plataform_name', 'products.product_name', 'customers.customer_name', 'postback_event_types.postback_event_type')
                            ->join('products', 'products.id', 'postbacks.product_id')
                            ->join('plataform_configs', 'plataform_configs.id', 'products.plataform_config_id')
                            ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                            ->join('customers', 'customers.id', 'postbacks.customer_id')
                            ->join('postback_event_types', 'postback_event_types.id', 'postbacks.postback_event_type_id')
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.postbacks.index')
                    ->withPostbacks($postbacks);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\Postback  $Postback
     * @return \Illuminate\Http\Response
     */
    public function show(Postback $Postback)
    {
        return $this->getView('user.postbacks.show')
                    ->withPostback($Postback);
    }
}

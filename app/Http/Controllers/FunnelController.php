<?php

namespace App\Http\Controllers;

use App\Models\User\Funnel;
use App\Models\User\Product;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;

class FunnelController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public $fields = array(
        'product_name' => 'Produto',
        'active' => ['label' => 'Ativo', 'type' => 'bool']
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'name' => "Funis"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'product_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $funnels = Funnel::select(['funnels.*', 'products.product_name'])
                            ->join('products', 'products.id', 'funnels.product_id')
                            ->where('products.produtct_name', 'like', "%$request->searchField%")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $funnels = Funnel::select(['funnels.*', 'products.product_name'])
                            ->join('products', 'products.id', 'funnels.product_id')
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.funnels.index')
                    ->withFunnels($funnels);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Novo"
            ]
        ];

        /* $products = Product::doesntHave('funnel')
                            ->select('products.id', 'products.product_name')
                            ->where('produts.active', true)
                            ->orderBy('products.product_name', 'asc')
                            ->get(); */

        return $this->getView('user.funnels.create');
                    //->withProducts($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function show(Funnel $funnel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function edit(Funnel $funnel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Funnel $funnel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funnel $funnel)
    {
        //
    }
}

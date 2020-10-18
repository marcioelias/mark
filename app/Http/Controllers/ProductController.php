<?php

namespace App\Http\Controllers;

use App\Models\Plataform;
use App\Models\User\Funnel;
use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'product_name' => 'Produto',
            'plataform_name' => 'Plataforma',
            'product_price' => 'Preço',
            'active' => ['label' => 'Ativo', 'type' => 'bool']
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
                'name' => 'Cadastros'
            ],
            [
                'name' => "Produtos"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'product_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $products = Product::select(['products.*', 'plataforms.plataform_name'])
                            ->join('plataform_configs', 'plataform_configs.id', 'products.plataform_config_id')
                            ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                            ->where('product_name', 'like', "%$request->searchField%")
                            ->orWhere('plataform_name', 'like', "%$request->searchField%")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $products = Product::select(['products.*', 'plataforms.plataform_name'])
                            ->join('plataform_configs', 'plataform_configs.id', 'products.plataform_config_id')
                            ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.products.index')
                    ->withProducts($products);
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
                'link' => "/produt",
                'name' => "Produtos"
            ],
            [
                'name' => "Novo"
            ]
        ];

        $plataforms = PlataformConfig::select('plataform_configs.id', 'plataforms.plataform_name')
                                ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                                ->where('plataform_configs.active', true)
                                ->orderBy('plataforms.plataform_name')
                                ->get();

        $funnels = Funnel::SalesFunnel()
                    ->Active()
                    ->orderBy('funnel_description', 'asc')
                    ->get();

        return $this->getView('user.products.create')
                    ->withPlataforms($plataforms)
                    ->withFunnels($funnels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $userId = Auth::user()->id;
        $this->validate($request, [
            'product_name' => "required|unique:products,product_name,NULL,NULL,user_id,$userId",
            'product_price' => 'required|min:0',
            'plataform_config_id' => 'required',
            'plataform_code' => "required|unique:products,plataform_code,NULL,NULL,user_id,$userId,plataform_config_id,$request->plataform_config_id",
            'funnel_id' => 'required'
        ]);

        $product = new Product($request->all());
        $product->active = ($request->active ?? false) ? true : false;

        $product->save();

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/produt",
                'name' => "Produtos"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $plataforms = PlataformConfig::doesntHave('products')
                                ->select('plataform_configs.id', 'plataforms.plataform_name')
                                ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                                ->where('plataform_configs.active', true)
                                ->orWhere('plataform_configs.id', $product->plataform_config_id)
                                ->orderBy('plataforms.plataform_name')
                                ->get();

        $funnels = Funnel::SalesFunnel()
                    ->Active()
                    ->orderBy('funnel_description', 'asc')
                    ->get();

        return $this->getView('user.products.edit')
                    ->withFunnels($funnels)
                    ->withProduct($product)
                    ->withPlataforms($plataforms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $userId = Auth::user()->id;
        $this->validate($request, [
            'product_name' => "required|unique:products,product_name,$product->id,id,user_id,$userId",
            'product_price' => 'required|min:0',
            'plataform_config_id' => 'required',
            'plataform_code' => "required|unique:products,plataform_code,$product->id,id,user_id,$userId,plataform_config_id,$request->plataform_config_id",
            'funnel_id' => 'required'
        ]);

        $product->fill($request->all());
        $product->active = ($request->active ?? false) ? true : false;
        return response()->json($product->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return response()->json($product->delete());
    }

    /**
     * Get all products (JSON Format)
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductsJson() {
        $products = Product::orderBy('product_name', 'asc')->get();

        return response()->json($products);
    }
}

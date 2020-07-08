<?php

namespace App\Http\Controllers;

use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\Product;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RecursiveArrayIterator;

class FunnelController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'product_name' => 'Produto',
            'tag_name' => 'Tag',
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
                'name' => "Funis"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'product_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $funnels = Funnel::select(['funnels.*', 'products.product_name', 'tags.tag_name'])
                            ->join('products', 'products.id', 'funnels.product_id')
                            ->join('tags', 'tags.id', 'funnels.tag_id')
                            ->where('products.produtct_name', 'like', "%$request->searchField%")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $funnels = Funnel::select(['funnels.*', 'products.product_name', 'tags.tag_name'])
                            ->join('products', 'products.id', 'funnels.product_id')
                            ->join('tags', 'tags.id', 'funnels.tag_id')
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
        //Log::debug($request->all());

        DB::beginTransaction();

        try {
            $funnel = new Funnel([
                'product_id' => $request->product_id,
                'tag_id' => $request->tag_id,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                //Log::debug($funnel);
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $step) {
                    Log::debug($step);
                    $newStep = new FunnelStep([
                        'funnel_step_sequence' => $step['funnel_step_sequence'],
                        'funnel_step_description' => $step['funnel_step_description'],
                        'new_tag_id' => $step['new_tag_id'] ?? null
                    ]);
                    $funnel->steps()->save($newStep);
                    //Log::debug($newStep);

                    foreach ($step['actions'] as $action) {
                        $newStepAction = new FunnelStepAction([
                            'action_type_id' => $action['action_type_id'],
                            'action_sequence' => $action['action_sequence'],
                            'action_description' => $action['action_description'],
                            'action_data' => json_encode($action['action_data']),
                        ]);
                        $newStep->actions()->save($newStepAction);
                        //Log::debug($newStepAction);
                    }
                }

                DB::commit();
                //DB::rollBack();
            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            DB::rollBack();
        }


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
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        return $this->getView('user.funnels.edit')
                    ->withFunnel($funnel);
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

    public function getFunnelJson(Funnel $funnel) {
        $funnel = Funnel::with('steps.actions')->first();
        return response()->json($funnel);
    }
}

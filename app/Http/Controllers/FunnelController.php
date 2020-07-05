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
        Log::debug($request->all());

        DB::beginTransaction();

        try {
            $funnel = new Funnel([
                'product_id' => $request->product_id,
                'tag_id' => $request->tag_id,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $sequence => $step) {
                    $newStep = new FunnelStep([
                        'funnel_step_sequence' => $sequence,
                        'funnel_step_description' => $step['data']['name'],
                        'new_tag_id' => $step['data']['new_tag'] ?? null
                    ]);
                    $funnel->steps()->save($newStep);

                    foreach ($step['actions'] as $actionSequence => $action) {
                        $newStepAction = new FunnelStepAction([
                            'action_type_id' => $action['actionType']['id'],
                            'action_sequence' => $actionSequence,
                            'action_data' => json_encode($action['actionData']),
                        ]);
                        $newStep->actions()->save($newStepAction);
                    }
                }

                DB::commit();
                //DB::rollBack();
            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
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

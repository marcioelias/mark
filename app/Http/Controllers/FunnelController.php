<?php

namespace App\Http\Controllers;

use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\Product;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
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
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'product_id' => "required",
            'tag_id' => "required|unique:funnels,tag_id,NULL,NULL,product_id,$request->product_id",
        ], [],
        [
            'product_id' => 'Produto',
            'tag_id' => 'Tag'
        ]);

        DB::beginTransaction();

        try {
            $funnel = new Funnel([
                'product_id' => $request->product_id,
                'tag_id' => $request->tag_id,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $step) {
                    $newStep = new FunnelStep([
                        'funnel_step_sequence' => $step['funnel_step_sequence'],
                        'funnel_step_description' => $step['funnel_step_description'],
                        'new_tag_id' => $step['new_tag_id'] ?? null,
                        'delay_days' => $step['delay_days'] ?? 0,
                        'delay_hours' => $step['delay_hours'] ?? 0
                    ]);
                    try {
                        $funnel->steps()->save($newStep);
                    } catch (Exception $e) {
                        throw $e;
                    }

                    foreach ($step['actions'] as $action) {
                        $jsonData = $action['action_data'];
                        $images = Arr::get($action['action_data'], 'options.images');
                        if ($images) {
                            $newStepAction = $newStep->actions()->create([
                                'action_type_id' => $action['action_type_id'],
                                'action_sequence' => $action['action_sequence'],
                                'action_description' => $action['action_description'],
                                'action_data' => [],
                            ]);

                            foreach ($images as $image) {
                                $newStepAction->addMediaFromBase64($image)->toMediaCollection('mail-images');
                                $mediaItems = $newStepAction->load('media')->getMedia('mail-images');
                                $jsonData['data'] = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $jsonData['data']);
                            }
                            $jsonData['options']['images'] = [];

                            $newStepAction->action_data = $jsonData;

                            $newStepAction->save();
                        } else {
                            $newStepAction = new FunnelStepAction([
                                'action_type_id' => $action['action_type_id'],
                                'action_sequence' => $action['action_sequence'],
                                'action_description' => $action['action_description'],
                                'action_data' => $action['action_data'],
                            ]);

                            try {
                                $newStep->actions()->save($newStepAction);
                            } catch (Exception $e) {
                                throw $e;
                            }
                        }
                    }
                }

                DB::commit();

                return response()->json(['redirect' => route('funnel.index')]);

            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            DB::rollBack();
            return response()->json($e->getMessage());
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
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Visualizar Funil"
            ]
        ];

        return $this->getView('user.funnels.show')
                    ->withFunnel($funnel->load(['product', 'tag', 'steps.actions']));
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
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'product_id' => "required",
            'tag_id' => "required|unique:funnels,tag_id,$funnel->id,id,product_id,$request->product_id",
        ], [],
        [
            'product_id' => 'Produto',
            'tag_id' => 'Tag'
        ]);

        DB::beginTransaction();

        try {
            Log::debug($request->all());
            $funnel->fill([
                'product_id' => $request->product_id,
                'tag_id' => $request->tag_id,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $step) {
                    $newStep = FunnelStep::updateOrCreate(
                        [
                            'id' => $step['id']
                        ],
                        [
                            'funnel_id' => $funnel->id,
                            'funnel_step_sequence' => $step['funnel_step_sequence'],
                            'funnel_step_description' => $step['funnel_step_description'],
                            'new_tag_id' => $step['new_tag_id'] ?? null,
                            'delay_days' => $step['delay_days'] ?? 0,
                            'delay_hours' => $step['delay_hours'] ?? 0
                        ]
                    );

                    foreach ($step['actions'] as $action) {
                        if ($action['deleted']) {
                            FunnelStepAction::find($action['id'])->delete();
                        } else {
                            $jsonData = $action['action_data'];
                            $images = Arr::get($action['action_data'], 'options.images');
                            if ($images) {
                                $newStepAction = FunnelStepAction::updateOrCreate(
                                    ['id' => $action['id']],
                                    [
                                        'funnel_step_id' => $newStep->id,
                                        'action_type_id' => $action['action_type_id'],
                                        'action_sequence' => $action['action_sequence'],
                                        'action_description' => $action['action_description'],
                                        'action_data' => [],
                                        'deleted' => $action['deleted']
                                    ]
                                );

                                foreach ($images as $image) {
                                    $newStepAction->addMediaFromBase64($image)->toMediaCollection('mail-images');
                                    $mediaItems = $newStepAction->load('media')->getMedia('mail-images');
                                    $jsonData['data'] = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $jsonData['data']);
                                }

                                $jsonData['options']['images'] = [];

                                $newStepAction->action_data = $jsonData;

                                $newStepAction->save();
                            } else {
                                $newStepAction = FunnelStepAction::updateOrCreate(
                                    ['id' => $action['id']],
                                    [
                                        'funnel_step_id' => $newStep->id,
                                        'action_type_id' => $action['action_type_id'],
                                        'action_sequence' => $action['action_sequence'],
                                        'action_description' => $action['action_description'],
                                        'action_data' => $action['action_data'],
                                        'deleted' => $action['deleted']
                                    ]
                                );
                            }
                        }
                    }
                }

                DB::commit();

                return response()->json(['redirect' => route('funnel.index')]);

            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funnel $funnel)
    {
        return response()->json($funnel->delete());
    }

    public function getFunnelJson(Funnel $funnel) {
        return response()->json($funnel->load('steps.actions'));
    }
}

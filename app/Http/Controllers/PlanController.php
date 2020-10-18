<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Plan;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'plan_name' => 'Plano',
            'num_postbacks' => 'Mum. Postbacks',
            'whatsapp_enabled' => ['label' => 'Whatsapp', 'type' => 'bool'],
            'plan_value' => ['label' => 'Valor', 'type' => 'decimal', 'decimais' => 2]
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
                'name'=>"Planos"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'plan_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $plans = Plan::where('plan_name', 'like', "%$request->searchField%")
                        ->OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $plans = Plan::OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('plans.index')
                    ->withPlans($plans);
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
                'link'=>"/admin/plan",
                'name'=>"Planos"
            ],
            [
                'name'=>"Novo"
            ]
        ];
        $features = Feature::orderBy('order', 'asc')->get();
        return $this->getView('plans.create')->withFeatures($features);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
/*         return response()->json($request->all());
 */
        $this->validate($request, [
            'marketplace_code' => 'required|unique:plans',
            'plan_name' => 'required',
            'plan_value' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $plan = new Plan($request->all());
            $plan->save();

            $features = Feature::all();
            $enableds = $request->enabled;
            $limits = $request->limit;
            foreach ($features as $feature) {
                $plan->features()->attach($feature->id, [
                    'enabled' => Arr::exists($enableds, $feature->id),
                    'limit' => Arr::exists($limits, $feature->id) ? $limits[$feature->id] : 0
                ]);
            }

            DB::commit();
            return response()->json(true);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link'=>"/admin/plan",
                'name'=>"Planos"
            ],
            [
                'name'=>"Alterar"
            ]
        ];

        return $this->getView('plans.edit')->withPlan($plan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'marketplace_code' => "required|unique:plans,marketplace_code,$plan->id,id",
            'plan_name' => 'required',
            'plan_value' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $plan->fill($request->all());
            $plan->save();

            foreach (Feature::all() as $feature) {
                $plan->features()->updateExistingPivot($feature->id, [
                    'enabled' => Arr::exists($request->enabled, $feature->id),
                    'limit' => Arr::exists($request->limit, $feature->id) ? $request->limit[$feature->id] : 0
                ]);
            }

            DB::commit();

            return response()->json(true);
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        return response()->json($plan->delete());
    }
}

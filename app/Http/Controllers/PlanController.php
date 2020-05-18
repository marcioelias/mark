<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public $fields = array(
        'plan_name' => 'Plano',
        'num_postbacks' => 'Mum. Postbacks',
        'plan_value' => ['label' => 'Valor', 'type' => 'decimal', 'decimais' => 2]
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
        return $this->getView('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'marketplace_code' => 'required|unique:plans',
            'plan_name' => 'required',
            'num_postbacks' => 'required',
            'plan_value' => 'required'
        ]);

        $plan = new Plan($request->all());
        return response()->json($plan->save());
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
            'num_postbacks' => 'required',
            'plan_value' => 'required'
        ]);

        $plan->fill($request->all());
        return response()->json($plan->save());
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

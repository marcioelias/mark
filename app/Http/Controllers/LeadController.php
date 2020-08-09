<?php

namespace App\Http\Controllers;

use App\Models\User\FunnelStep;
use App\Models\User\Lead;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'tag_name' => 'Tag'
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
                'name' => 'Leads'
            ],
            [
                'name' => "Leads"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'tag_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $tags = Lead::where('tag_name', 'like', "%$request->searchField%")
                        ->OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $tags = Lead::OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('user.tags.index')
                    ->withTags($tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\User\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }

    public function getLeadsFromStep(FunnelStep $funnelStep) {
        return response()->json(
            $funnelStep->leads()
                    ->with([
                        'customer',
                        'leadStatus',
                        'schedules' => function($query) use ($funnelStep) {
                            $query->where('funnel_step_id', $funnelStep->id)
                            ->with('action');
                        }
                    ])->paginate(3));
    }
}

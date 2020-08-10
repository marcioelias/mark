<?php

namespace App\Http\Controllers;

use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\Lead;
use App\Models\User\Schedule;
use App\Traits\LayoutConfigTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getLeadsFromStep(Request $request, FunnelStep $funnelStep) {
        $leads = Lead::select('leads.*')
                    ->join('customers', 'customers.id', 'leads.customer_id')
                    ->where('leads.funnel_step_id', $funnelStep->id)
                    ->where(function ($query) use ($request) {
                        $query->where('customers.customer_name', 'like', "%$request->searchValue%")
                             ->orWhere('leads.transaction_code', $request->searchValue);
                    })
                    ->orderBy($request->orderBy, $request->orderType)
                    ->get();

        foreach ($leads as $lead) {
            $rows[] =  Lead::with(['customer', 'leadStatus'])
                            ->withHas('schedules', function($query) use ($lead) {
                                $query->where('funnel_step_id', $lead->funnel_step_id)
                                    ->with('action');
                            })
                            ->find($lead->id);
        }

        $result = (new Collection($rows ?? []))->paginate(3);

        return response()->json($result);
    }
}

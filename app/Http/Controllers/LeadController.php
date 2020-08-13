<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use App\Models\PaymentType;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\Lead;
use App\Models\User\Product;
use App\Models\User\Schedule;
use App\Models\User\Tag;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'created_at' => ['label' => 'Dt. Compra', 'type' => 'datetime'],
            'transaction_code' => 'Transação',
            'customer_name' => 'Cliente',
            'payment_type' => 'Forma de Pgto',
            'value' => ['label' => 'Valor', 'type' => 'decimal', 'decimais' => 2],
            'status' => 'Status'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::debug($request->all());

        $this->breadcrumbs = [
            [
                'name' => 'Leads'
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'created_at',
            'order_type' => 'desc'
        ]);

        /* filters */
        if (isset($request->lead_dt_begin_submit) && isset($request->lead_dt_end_submit)) {
            $whereData = '`leads`.`created_at` between \''.Carbon::parse($request->lead_dt_begin_submit)->startOfDay()->format('Y/m/d H:n:s').'\' and \''.Carbon::parse($request->lead_dt_end_submit)->endOfDay()->format('Y/m/d H:n:s').'\'';
        } else if (isset($request->lead_dt_begin_submit) && !isset($request->lead_dt_end_submit)) {
            $whereData = '`leads`.`created_at` >= \''.Carbon::parse($request->lead_dt_begin_submit)->startOfDay()->format('Y/m/d H:n:s').'\'';
        } else if (!isset($request->lead_dt_begin_submit) && isset($request->lead_dt_end_submit)) {
            $whereData = '`leads`.`created_at` <= \''.Carbon::parse($request->lead_dt_end_submit)->endOfDay()->format('Y/m/d H:n:s').'\'';
        } else {
            $whereData = '1 = 1';
        }

        if (isset($request->tag_id) && $request->tag_id === 'none') {
            $whereTag = '`leads`.`tag_id` is null';
        } else if (isset($request->tag_id) && $request->tag_id != 'none') {
            $whereTag = '`leads`.`tag_id` = \''.$request->tag_id.'\'';
        } else {
            $whereTag = '1 = 1';
        }

        if (isset($request->product_id)) {
            $whereProduct = '`leads`.`product_id` = \''.$request->product_id.'\'';
        } else {
            $whereProduct = '1 = 1';
        }

        if (isset($request->payment_type_id)) {
            $wherePaymentType = '`leads`.`payment_type_id` = \''.$request->payment_type_id.'\'';
        } else {
            $wherePaymentType = '1 = 1';
        }

        if (isset($request->lead_status_id)) {
            $whereLeadStatus = '`leads`.`lead_status_id` = \''.$request->lead_status_id.'\'';
        } else {
            $whereLeadStatus = '1 = 1';
        }

        if ($request->searchField) {
            $leads = Lead::select('leads.*',
                                  'customers.customer_name',
                                  'customers.customer_phone_number',
                                  'customers.customer_email',
                                  'payment_types.payment_type',
                                  'lead_statuses.status')
                            ->join('customers', 'customers.id', 'leads.customer_id')
                            ->join('payment_types', 'payment_types.id', 'leads.payment_type_id')
                            ->join('lead_statuses', 'lead_statuses.id', 'leads.lead_status_id')
                            ->where('customers.customer_name', 'like', "%$request->searchField%")
                            ->whereRaw($whereProduct)
                            ->whereRaw($wherePaymentType)
                            ->whereRaw($whereLeadStatus)
                            ->whereRaw($whereData)
                            ->whereRaw($whereTag)
                            ->orWhere('leads.transaction_code', "$request->searchField")
                            ->OrderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $leads = Lead::select('leads.*',
                                  'customers.customer_name',
                                  'customers.customer_phone_number',
                                  'customers.customer_email',
                                  'payment_types.payment_type',
                                  'lead_statuses.status')
                            ->join('customers', 'customers.id', 'leads.customer_id')
                            ->join('payment_types', 'payment_types.id', 'leads.payment_type_id')
                            ->join('lead_statuses', 'lead_statuses.id', 'leads.lead_status_id')
                            ->whereRaw($whereProduct)
                            ->whereRaw($wherePaymentType)
                            ->whereRaw($whereLeadStatus)
                            ->whereRaw($whereData)
                            ->whereRaw($whereTag)
                            ->OrderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }
        $products = Product::active()->orderBy('product_name', 'ASC')->get();
        $paymentTypes = PaymentType::orderBy('payment_type', 'ASC')->get();
        $leadStatuses = LeadStatus::orderBy('status', 'ASC')->get();
        $tags = Tag::orderBy('tag_name', 'ASC')->get();
        return $this->getIndex('user.leads.index')
                    ->withLeads($leads)
                    ->withProducts($products)
                    ->withPaymentTypes($paymentTypes)
                    ->withLeadStatuses($leadStatuses)
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
        return $this->getView('user.leads.show')
                    ->withLead($lead);
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

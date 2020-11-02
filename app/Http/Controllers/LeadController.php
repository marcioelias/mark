<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use App\Models\User\Lead;
use App\Models\User\LeadStatus;
use App\Models\User\Product;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
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
        //Log::debug($request->all());

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
        $rawQuery = false;
        $rawQueryParam = [];
        if (isset($request->lead_dt_begin_submit) && isset($request->lead_dt_end_submit)) {
            $rawQuery .= ($rawQuery) ? ' and leads.created_at between ? and ?' : 'leads.created_at between ? and ?';
            $rawQueryParam[] =  Carbon::parse($request->lead_dt_begin_submit)->startOfDay()->format('Y/m/d H:n:s');
            $rawQueryParam[] = Carbon::parse($request->lead_dt_end_submit)->endOfDay()->format('Y/m/d H:n:s');
        } else if (isset($request->lead_dt_begin_submit) && !isset($request->lead_dt_end_submit)) {
            $rawQuery .= ($rawQuery) ? ' and leads.created_at >= ?' : 'leads.created_at >= ?';
            $rawQueryParam[] =  Carbon::parse($request->lead_dt_begin_submit)->startOfDay()->format('Y/m/d H:n:s');
        } else if (!isset($request->lead_dt_begin_submit) && isset($request->lead_dt_end_submit)) {
            $rawQuery .= ($rawQuery) ? ' and leads.created_at <= ?' : 'leads.created_at <= ?';
            $rawQueryParam[] = Carbon::parse($request->lead_dt_end_submit)->endOfDay()->format('Y/m/d H:n:s');
        }

        if (isset($request->product_id)) {
            $rawQuery .= ($rawQuery) ? ' and leads.product_id = ?' : 'leads.product_id = ?';
            $rawQueryParam[] = $request->product_id;
        }

        if (isset($request->payment_type_id)) {
            $rawQuery .= ($rawQuery) ? ' and leads.payment_type_id = ?' : 'leads.payment_type_id = ?';
            $rawQueryParam[] = $request->payment_type_id;
        }

        if (isset($request->lead_status_id)) {
            $rawQuery .= ($rawQuery) ? ' and leads.lead_status_id = ?' : 'leads.lead_status_id = ?';
            $rawQueryParam[] = $request->lead_status_id;
        }

        if (!$rawQuery) {
            $rawQuery = '? = ?';
            $rawQueryParam = [1, 1];
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
                            ->orWhere('leads.transaction_code', "$request->searchField")
                            ->whereRaw($rawQuery, $rawQueryParam)
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
                            ->whereRaw($rawQuery, $rawQueryParam)
                            ->OrderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }
        $products = Product::active()->orderBy('product_name', 'ASC')->get();
        $paymentTypes = PaymentType::orderBy('payment_type', 'ASC')->get();
        $leadStatuses = LeadStatus::orderBy('status', 'ASC')->get();
        return $this->getIndex('user.leads.index')
                    ->withLeads($leads)
                    ->withProducts($products)
                    ->withPaymentTypes($paymentTypes)
                    ->withLeadStatuses($leadStatuses);
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
}

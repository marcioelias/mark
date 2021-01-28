<?php

namespace App\Http\Controllers;

use App\Models\User\SentMessage;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SentMessageController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'created_at' => ['label' => 'Dt. Envio', 'type' => 'datetime'],
            'customer_name' => 'Cliente',
            'product_name' => 'Produto',
            'postback_event_type' => 'Evento',
            'action_type_description' => 'Tipo de Msg.',
            'is_successful' => ['label' => 'Sucesso', 'type' => 'bool']
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
                'name' => 'Enviados'
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'created_at',
            'order_type' => 'desc'
        ]);

        if ($request->searchField) {
            $sentMessages = SentMessage::select('sent_messages.*', 'customers.customer_name', 'action_types.action_type_description', 'postback_event_types.postback_event_type', 'products.product_name')
                                        ->join('leads', 'leads.id', 'sent_messages.lead_id')
                                        ->join('customers', 'customers.id', 'leads.customer_id')
                                        ->join('funnel_step_actions', 'funnel_step_actions.id', 'sent_messages.funnel_step_action_id')
                                        ->join('funnel_steps', 'funnel_steps.id', 'funnel_step_actions.funnel_step_id')
                                        ->join('postback_event_types', 'postback_event_types.id', 'funnel_steps.postback_event_type_id')
                                        ->join('funnels', 'funnels.id', 'funnel_steps.funnel_id')
                                        ->join('products', 'products.id', 'leads.product_id')
                                        ->join('action_types', 'action_types.id', 'funnel_step_actions.action_type_id')
                                        ->where('customers.customer_name', 'like', "%$request->searchField%")
                                        ->orWhere('customers.customer_phone_number', 'like', "%$request->searchField%")
                                        ->orWhere('customers.customer_email', 'like', "%$request->searchField%")
                                        ->OrderBy($this->orderField, $this->orderType)
                                        ->paginate($this->paginate);
        } else {
            $sentMessages = SentMessage::select('sent_messages.*', 'customers.customer_name', 'action_types.action_type_description', 'postback_event_types.postback_event_type', 'products.product_name')
                                        ->join('leads', 'leads.id', 'sent_messages.lead_id')
                                        ->join('customers', 'customers.id', 'leads.customer_id')
                                        ->join('funnel_step_actions', 'funnel_step_actions.id', 'sent_messages.funnel_step_action_id')
                                        ->join('funnel_steps', 'funnel_steps.id', 'funnel_step_actions.funnel_step_id')
                                        ->join('postback_event_types', 'postback_event_types.id', 'funnel_steps.postback_event_type_id')
                                        ->join('funnels', 'funnels.id', 'funnel_steps.funnel_id')
                                        ->join('products', 'products.id', 'leads.product_id')
                                        ->join('action_types', 'action_types.id', 'funnel_step_actions.action_type_id')
                                        ->OrderBy($this->orderField, $this->orderType)
                                        ->paginate($this->paginate);

        }

        //dd($sentMessages);
        return $this->getIndex('user.sent_messages.index')
                    ->withSentMessages($sentMessages);
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
     * @param  \App\Models\User\SentMessage  $sentMessage
     * @return \Illuminate\Http\Response
     */
    public function show(SentMessage $sentMessage)
    {
        return $this->getView('user.sent_messages.show')
            ->withSentMessage($sentMessage->load(['funnelStepAction.funnelStep.funnel', 'funnelStepAction.funnelStep.postbackEventType'])->load(['lead.customer', 'lead.product']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\SentMessage  $sentMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(SentMessage $sentMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\SentMessage  $sentMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SentMessage $sentMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\SentMessage  $sentMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SentMessage $sentMessage)
    {
        //
    }
}

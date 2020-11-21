<?php

namespace App\Listeners;

use App\Constants\CustomerStatuses;
use App\Events\OnAddLeadToStep;
use App\Events\OnLeadCreated;
use App\Models\User\Customer;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepLead;
use App\Models\User\Postback;
use App\Models\User\Schedule;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DoOnLeadCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnLeadCreated  $event
     * @return void
     */
    public function handle(OnLeadCreated $event)
    {
        $this->addLeadToStep($event->postback);
    }

    /**
     * Add the Lead to corresponding event type setp on the funnel, if it exists
     *
     * @param Postback $postback
     * @return void
     */
    private function addLeadToStep(Postback $postback) {
        try {
            $this->updateCustomerStatus($postback->lead->customer);
            $salesFunnel = $postback->product->funnel;
            if ($salesFunnel) {
                Log::info('Encontrou funil de vendas: '.$salesFunnel->id);
                $funnelStep = FunnelStep::where('funnel_id', $salesFunnel->id)
                                    ->where('postback_event_type_id', $postback->postback_event_type_id)
                                    ->first();
                if ($funnelStep) {
                    Log::info('Encontrou evento do funil: '.$funnelStep->id);
                    $funnelStepLead = new FunnelStepLead([
                        'user_id' => $postback->user_id,
                        'funnel_step_id' => $funnelStep->id,
                        'lead_id' => $postback->lead_id
                    ]);

                    $funnelStepLead->save();

                    /**
                     * Dispatch the event indicating that this Lead was assigned with a Funnel Step
                     */
                    event(new OnAddLeadToStep($postback, $funnelStepLead));
                }
            }
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    /**
     * Update customer, setting the current status to Active
     *
     * @param Customer $customer
     * @return void
     */
    private function updateCustomerStatus(Customer $customer) {
        $customer->customer_status_id = CustomerStatuses::ACTIVE;
        $customer->save();
    }
}

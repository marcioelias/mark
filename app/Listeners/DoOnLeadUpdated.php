<?php

namespace App\Listeners;

use App\Constants\CustomerStatuses;
use App\Constants\PostbackEventType;
use App\Events\OnAddLeadToStep;
use App\Events\OnLeadUpdated;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepLead;
use App\Models\User\Postback;
use App\Models\User\Schedule;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DoOnLeadUpdated
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
     * @param  OnLeadUpdated  $event
     * @return void
     */
    public function handle(OnLeadUpdated $event)
    {
        try {
            $funnelSteplead = $event->postback->lead->funnelStepLeads()->where('active', true)->orderBy('created_at', 'desc')->first();

            if ($funnelSteplead) {
                $this->updateFunnelStepLead($funnelSteplead, $event->postback);
            }
        } catch (Exception $e) {
            Log::emergency($e->getMessage());
            Log::debug($e);
        }
    }

    /**
     * Update Lead to new step on funnel based on the event type of the postback
     *
     * @param FunnelStepLead $funnelSteplead
     * @param Postback $postback
     * @return void
     */
    public function updateFunnelStepLead(FunnelStepLead $funnelSteplead, Postback $postback) {
        if (!$this->postbackWithNewEventType($funnelSteplead, $postback)) {
            DB::beginTransaction();
            try {
                $this->inactiveCurrentStep($funnelSteplead);
                $this->removePendingSchedulesCurrentStep($funnelSteplead);
                $this->addLeadToNewStep($postback);
                $this->updateCustomerStatus($postback);

                DB::commit();
            } catch (Exception $e) {
                Log::emergency($e->getMessage());
                DB::rollBack();
            }
        }
    }

    /**
     * Check if the Lead change the status based on the postback event type
     *
     * @param FunnelStepLead $funnelStepLead
     * @param Postback $postback
     * @return bool
     */
    public function postbackWithNewEventType(FunnelStepLead $funnelStepLead, Postback $postback): bool
    {
        return  ((string) $funnelStepLead->funnelStep->postback_event_type_id === (string) $postback->postback_event_type_id);
    }

    /**
     * Deactivate current step, has nothing else to do here
     *
     * @param FunnelStepLead $funnelStepLead
     * @return void
     */
    public function inactiveCurrentStep(FunnelStepLead $funnelStepLead) {
        $funnelStepLead->active = false;
        $funnelStepLead->save();
    }

    /**
     * Add Lead to new step on the funnel based on the postback event type
     *
     * @param Postback $postback
     * @return void
     */
    public function addLeadToNewStep(Postback $postback) {
        $funnelStep = $this->getFunnelStep($postback);
        if ($funnelStep) {
            $funnelStepLead = new FunnelStepLead([
                'user_id' => $postback->user_id,
                'funnel_step_id' => $funnelStep->id,
                'lead_id' => $postback->lead_id
            ]);

            $funnelStepLead->save();

            event(new OnAddLeadToStep($postback, $funnelStepLead));
        }
    }

    /**
     * Get the funnel step based on the postback event type
     *
     * @param Postback $postback
     * @return FunnelStep
     */
    public function getFunnelStep(Postback $postback): FunnelStep
    {
        $salesFunnel = $this->getFunnel($postback);
        return FunnelStep::where('funnel_id', $salesFunnel->id)
                                ->where('postback_event_type_id', $postback->postback_event_type_id)
                                ->first();
    }

    /**
     * Get the funnel based on the relationship with product present at postback
     *
     * @param Postback $postback
     * @return Funnel
     */
    public function getFunnel(Postback $postback): Funnel
    {
        return $postback->product->funnel;
    }

    /**
     * Remove any pending schedule action for the given step. This is usefull when the Lead
     * change status, going to a new step, and has pending tasks of prior step that do not
     * will be executed anymore.
     *
     * @param FunnelStepLead $funnelStepLead
     * @return void
     */
    public function removePendingSchedulesCurrentStep(FunnelStepLead $funnelStepLead) {
        $schedules = Schedule::where('lead_id', $funnelStepLead->lead_id)
                            ->where('funnel_step_id', $funnelStepLead->funnel_step_id)
                            ->Pending()
                            ->get();

        foreach ($schedules as $schedule) {
            $schedule->delete();
        }
    }

    /**
     * Update customer status based on the current status of the Lead
     *
     * @param Postback $postback
     * @return void
     */
    private function updateCustomerStatus(Postback $postback) {
        $customer = $postback->customer;
        switch ($postback->postback_event_type_id) {
            case PostbackEventType::APPROVED:
            case PostbackEventType::CANCELED:
            case PostbackEventType::REFUNDED:
                $customer->customer_status_id = CustomerStatuses::INACTIVE;
                break;
            case PostbackEventType::BILLET_PRINTED:
            case PostbackEventType::DISPUTE:
            case PostbackEventType::WAITING_PAYMENT:
                $customer->customer_status_id = CustomerStatuses::ACTIVE;
                break;
        }

        $customer->save();
    }
}

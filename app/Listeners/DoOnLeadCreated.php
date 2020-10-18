<?php

namespace App\Listeners;

use App\Events\OnAddLeadToStep;
use App\Events\OnLeadCreated;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepLead;
use App\Models\User\Postback;
use App\Models\User\Schedule;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

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

    private function addLeadToStep(Postback $postback) {
        $salesFunnel = Funnel::where('user_id', $postback->user_id)->salesFunnel()->first();
        if ($salesFunnel) {
            $funnelStep = FunnelStep::where('funnel_id', $salesFunnel->id)
                                ->where('postback_event_type_id', $postback->postback_event_type_id)
                                ->first();
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
    }

    /* private function scheduleAction(Postback $postback) {

        Schedule::create([
            'lead_id' => $postback->lead_id,
            'funnel_step_id' => $postback->lead->funnel_step_id,
            'funnel_step_action_id' => $postback->lead->funnelStep->firstAction(),
            'start_at' => $this->getScheduleStartTime($postback)
        ]);
    }

    private function getScheduleStartTime(Postback $postback) {
        $funnelStep = $postback->lead->funnelStep;
        $action = $funnelStep->firstAction();
        $leadCreatedAt = CarbonImmutable::parse($postback->lead->created_at);
        $actionData = $action->action_data;
        $daysAfter = Arr::get($actionData, 'options.days_after', 0);
        $startAt =  Arr::get($actionData, 'options.start_time', '00:00').':00';
        $endAt = Arr::get($actionData, 'options.start_time', '23:59').':59';
        $delayMinutes = Arr::get($actionData, 'options.delay_minutes', 0);


    } */


}

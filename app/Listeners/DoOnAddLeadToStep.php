<?php

namespace App\Listeners;

use App\Events\OnAddLeadToStep;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
use App\Models\User\Lead;
use App\Models\User\Postback;
use App\Models\User\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class DoOnAddLeadToStep
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
     * @param  OnAddLeadToStep  $event
     * @return void
     */
    public function handle(OnAddLeadToStep $event)
    {
        $this->scheduleAction($event->postback, $event->funnelStepLead);
    }

    private function scheduleAction(Postback $postback, FunnelStepLead $funnelStepLead) {
        $action = $this->getAction($funnelStepLead, $postback);

        $schedule = new Schedule([
            'user_id' => $postback->user_id,
            'lead_id' => $postback->lead_id,
            'funnel_step_id' => $funnelStepLead->funnelStep->id,
            'funnel_step_action_id' => $action->id,
            'start_at' => $this->getScheduleStartAt($action, $funnelStepLead),
            'start_period' => $this->getSheduleStartPeriod($action),
            'end_period' => $this->getSheduleEndPeriod($action),
            'delay_before_start' => $this->getDelayBeforeStart($action),
        ]);

        $schedule->save();
    }

    private function getScheduleStartAt(FunnelStepAction $funnelStepAction, FunnelStepLead $funnelStepLead) {
        return Carbon::parse($funnelStepLead->created_at)->startOfDay()
                                                         ->addDays($funnelStepAction->action_data['options']['days_after'] ?? 0);
    }

    private function getSheduleStartPeriod(FunnelStepAction $funnelStepAction) {
        return Carbon::parse($funnelStepAction->action_data['options']['start_time'] ?? '00:00')->toTimeString();
    }

    private function getSheduleEndPeriod(FunnelStepAction $funnelStepAction) {
        return Carbon::parse($funnelStepAction->action_data['options']['end_time'] ?? '23:59')->toTimeString();
    }

    private function getDelayBeforeStart(FunnelStepAction $funnelStepAction) {
        return $funnelStepAction->action_data['options']['delay_minutes'] ?? 0;
    }

    private function getAction(FunnelStepLead $funnelStepLead, Postback $postback) {
        return $funnelStepLead->funnelStep->actions()->orderBy('action_sequence')->first();
    }
}

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
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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

    /**
     * Create a schedule task to handle the action
     *
     * @param Postback $postback
     * @param FunnelStepLead $funnelStepLead
     * @return void
     */
    private function scheduleAction(Postback $postback, FunnelStepLead $funnelStepLead) {
        $action = $this->getAction($funnelStepLead);

        if ($action) {
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
    }

    /**
     * Get start datetime of an Action based on the number of the days that the action
     * is configured to run after the ingress event of the step
     *
     * @param FunnelStepAction $funnelStepAction
     * @param FunnelStepLead $funnelStepLead
     * @return DateTime
     */
    private function getScheduleStartAt(FunnelStepAction $funnelStepAction, FunnelStepLead $funnelStepLead): DateTime
    {
        return Carbon::parse($funnelStepLead->created_at)->startOfDay()
                                                         ->addDays($funnelStepAction->action_data['options']['days_after'] ?? 0);
    }

    /**
     * Get minimum time of the day to start running a action, in format HH:nn
     *
     * @param FunnelStepAction $funnelStepAction
     * @return string
     */
    private function getSheduleStartPeriod(FunnelStepAction $funnelStepAction): string
    {
        return Carbon::parse($funnelStepAction->action_data['options']['start_time'] ?? '00:00')->toTimeString();
    }

    /**
     * Get maximum time of the day to start running a action, in format HH:nn
     *
     * @param FunnelStepAction $funnelStepAction
     * @return string
     */
    private function getSheduleEndPeriod(FunnelStepAction $funnelStepAction): string
    {
        return Carbon::parse($funnelStepAction->action_data['options']['end_time'] ?? '23:59')->toTimeString();
    }

    /**
     * Get te number of minutes that the action must dalay his execution
     *
     * @param FunnelStepAction $funnelStepAction
     * @return integer
     */
    private function getDelayBeforeStart(FunnelStepAction $funnelStepAction): int
    {
        return $funnelStepAction->action_data['options']['delay_minutes'] ?? 0;
    }


    /**
     * Get the first action to be executed at current step
     *
     * @param FunnelStepLead $funnelStepLead
     * @return FunnelStepAction
     */
    private function getAction(FunnelStepLead $funnelStepLead): FunnelStepAction
    {
        return $funnelStepLead->funnelStep->actions()->orderBy('seconds_after', 'asc')->first();
    }
}

<?php

namespace App\Listeners;

use App\Constants\ScheduleStatus;
use App\Events\LeadGoToStep;
use App\Events\NotificationSent;
use App\Models\User\FunnelStep;
use App\Models\User\Lead;
use App\Models\User\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SetNotificationDone
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
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        $event->schedule->finished_at = Carbon::now();
        $event->schedule->schedule_status_id = ScheduleStatus::FINISHED;
        $event->schedule->save();

        $this->moveLeadToNextStep($event->schedule->lead);
    }

    public function moveLeadToNextStep(Lead $lead) {
        if (!$this->hasPendingSchedules($lead)) {
            $leadId = $lead->id;
            $funnel = $lead->funnelStep->funnel;
            $nextStep = $funnel->nextStep($lead->funnelStep->funnel_step_sequence);
            $lead->last_step_finished_at = Carbon::now();
            if ($nextStep) {
                $lead->funnel_step_id = $nextStep->id;
                $lead->save();
                event(new LeadGoToStep(Lead::find($leadId)));
            } else {
                $lead->save();
            }
        }
    }

    public function hasPendingSchedules(Lead $lead) {
        return Schedule::where('lead_id', $lead->id)
                            ->where('funnel_step_id', $lead->funnel_step_id)
                            ->pending()
                            ->count() > 0;
    }
}

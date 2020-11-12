<?php

namespace App\Listeners;

use App\Constants\ScheduleStatus;
use App\Events\LeadGoToStep;
use App\Events\NotificationSent;
use App\Models\User\Action;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
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

        $this->accounting($event);

        //tem que ir para a próxima ação!!!!!!!!
        $this->scheduleNextAction($event->schedule);
    }

    private function scheduleNextAction(Schedule $schedule) {
        $nextAction = $this->getNextAction($schedule->funnelStepAction);
        if ($nextAction) {
            $this->scheduleAction($schedule, $nextAction);
        }
    }

    private function scheduleAction(Schedule $schedule, FunnelStepAction $funnelStepAction) {
        $schedule = new Schedule([
            'user_id' => $schedule->user_id,
            'lead_id' => $schedule->lead_id,
            'funnel_step_id' => $schedule->funnel_step_id,
            'funnel_step_action_id' => $funnelStepAction->id,
            'start_at' => $this->getScheduleStartAt($funnelStepAction, FunnelStepLead::where('lead_id', $schedule->lead_id)->first()),
            'start_period' => $this->getSheduleStartPeriod($funnelStepAction),
            'end_period' => $this->getSheduleEndPeriod($funnelStepAction),
            'delay_before_start' => $this->getDelayBeforeStart($funnelStepAction),
        ]);

        $schedule->save();
    }

    private function getNextAction(FunnelStepAction $funnelStepAction) {
        return FunnelStep::find($funnelStepAction->funnel_step_id)
                    ->actions()
                    ->where('seconds_after', '>', $funnelStepAction->seconds_after)
                    ->orderBy('seconds_after', 'asc')
                    ->first();
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

    private function accounting(NotificationSent $event) {
        $action = new Action([
            'user_id' => $event->schedule->user_id,
            'action_type_id' => $event->schedule->funnelStepAction->action_type_id,
            'action_data' => $event->notificationData,
            'executed_at' => Carbon::now()
        ]);

        $action->save();
    }
}

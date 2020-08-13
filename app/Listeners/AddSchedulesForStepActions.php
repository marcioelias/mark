<?php

namespace App\Listeners;

use App\Constants\ScheduleStatus;
use App\Events\LeadGoToStep;
use App\Models\User\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AddSchedulesForStepActions
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
     * @param  LeadGoToStep  $event
     * @return void
     */
    public function handle(LeadGoToStep $event)
    {
        try {
            $step = $event->lead->funnelStep;
            foreach($step->actions as $action) {
                $actionData = $action->action_data;
                $lastStepFinishedAt = CarbonImmutable::parse($event->lead->stepFinishedAt());
                $startAt =  (Arr::get($actionData, 'options.period', [])[0] ?? 0).':00:00';
                $endAt = (Arr::get($actionData, 'options.period', [])[1] ?? 23).':59:59';
                $schedule = new Schedule([
                    'user_id' => $event->lead->user_id,
                    'lead_id' => $event->lead->id,
                    'funnel_step_id' => $step->id,
                    'funnel_step_action_id' => $action->id,
                    'start_at' => $lastStepFinishedAt->addDays($step->delay_days ?? 0)->addHours($step->delay_hours ?? 0),
                    'start_period' => $startAt,
                    'end_period' => $endAt,
                    'delay_before_start' => 0, //uso futuro, delay opcional para envio de sms
                    'shedule_status_id' => ScheduleStatus::PENDING
                ]);

                $schedule->save();
            }
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }
}

<?php

namespace App\Listeners;

use App\Constants\ActionTypes;
use App\Events\NewRunnableAction;
use App\Events\OnAddLeadToStep;
use App\Jobs\SendNotifications;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
use App\Models\User\Schedule;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DoOnNewRunnableAction
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
     * @param  NewRunnableAction  $event
     * @return void
     */
    public function handle(NewRunnableAction $event)
    {
        try {
            switch ($event->schedule->action->action_type_id) {
                case ActionTypes::SMS:
                case ActionTypes::EMAIL:
                case ActionTypes::WHATSAPP:
                    try {
                        SendNotifications::dispatch($event->schedule)->delay(Carbon::now()->addMinutes($event->schedule->delay_before_start ?? 0));
                    } catch (Exception $e) {
                        Log::emergency($e);
                    }
                    break;

                case ActionTypes::REMARKETING:
                    $this->remarketingLead($event->schedule);
                    break;
            }
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    /**
     * Put the lead on the remarketing funnel and create the propper schedules for the new actions
     *
     * @param Schedule $schedule
     * @return void
     */
    private function remarketingLead(Schedule $schedule) {
        $funnelStepLead = FunnelStepLead::where('lead_id', $schedule->lead_id)
                                    ->where('funnel_step_id', $schedule->funnel_step_id)
                                    ->first();

        if ($funnelStepLead) {
            $funnelStepLead->active = 0;
            $funnelStepLead->save();
        }

        $funnel = Funnel::find($schedule->funnelStepAction->action_data['data']);
        if ($funnel) {
            $funnelStep = $funnel->steps()->first();
            if ($funnelStep) {
                $funnelStepLead = FunnelStepLead::create([
                    'user_id' => $schedule->user_id,
                    'funnel_step_id' => $funnelStep->id,
                    'lead_id' => $schedule->lead_id
                ]);

                $funnelStepLead->save();

                $this->scheduleAction($schedule, $funnelStepLead);
            }
        }
    }

    /**
     * Create a schedule task to handle the action
     *
     * @param Postback $postback
     * @param FunnelStepLead $funnelStepLead
     * @return void
     */
    private function scheduleAction(Schedule $schedule, FunnelStepLead $funnelStepLead) {
        $action = $this->getAction($funnelStepLead);

        $schedule = new Schedule([
            'user_id' => $schedule->user_id,
            'lead_id' => $schedule->lead_id,
            'funnel_step_id' => $funnelStepLead->funnelStep->id,
            'funnel_step_action_id' => $action->id,
            'start_at' => $this->getScheduleStartAt($action, $funnelStepLead),
            'start_period' => $this->getSheduleStartPeriod($action),
            'end_period' => $this->getSheduleEndPeriod($action),
            'delay_before_start' => $this->getDelayBeforeStart($action),
        ]);

        $schedule->save();
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
        return $funnelStepLead->funnelStep->actions()->orderBy('action_sequence', 'asc')->first();
    }
}

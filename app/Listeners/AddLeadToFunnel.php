<?php

namespace App\Listeners;

use App\Events\LeadGoToStep;
use App\Events\SetLeadTag;
use App\Models\User\Funnel;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddLeadToFunnel
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
     * @param  SetLeadTag  $event
     * @return void
     */
    public function handle(SetLeadTag $event)
    {
        try {
            $lead = $event->lead;
            if (!$lead->funnel_step_id) {
                $funnel = Funnel::byProductAndTag($lead->product_id, $lead->tag_id)->first();

                if ($funnel) {
                    $lead->funnel_step_id = $funnel->firstStep()->id;
                    $lead->save();

                    event(new LeadGoToStep($lead));
                }
            }
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }
}

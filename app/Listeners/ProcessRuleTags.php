<?php

namespace App\Listeners;

use App\Events\NewLeadCreated;
use App\Events\SetLeadTag;
use App\Models\User\TagRule;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessRuleTags
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
     * @param  NewLeadCreated  $event
     * @return void
     */
    public function handle(NewLeadCreated $event)
    {
        try {
            $rule = TagRule::where('product_id', $event->lead->product_id)
                        ->where('lead_status_id', $event->lead->lead_status_id)
                        ->firstOrFail();

            $event->lead->tag_id = $rule->tag_id;
            $event->lead->save();

            event(new SetLeadTag($event->lead));
        } catch (Exception $e) {
            //
        }
    }
}

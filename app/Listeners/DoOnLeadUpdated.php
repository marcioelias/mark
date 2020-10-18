<?php

namespace App\Providers;

use App\Providers\OnLeadUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        //
    }
}

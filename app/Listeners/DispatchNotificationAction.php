<?php

namespace App\Listeners;

use App\Events\NewRunnableAction;
use App\Jobs\SendNotifications;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DispatchNotificationAction
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
        SendNotifications::dispatch($event->schedule)->delay(Carbon::now()->addMinutes($event->schedule->delay_before_start ?? 0));
    }
}

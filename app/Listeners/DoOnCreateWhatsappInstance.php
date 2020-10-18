<?php

namespace App\Listeners;

use App\Events\OnCreateWhatsappInstance;
use App\Whatsapp\WhatsappIntegration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DoOnCreateWhatsappInstance
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
     * @param  OnCreateWhatsappInstance  $event
     * @return void
     */
    public function handle(OnCreateWhatsappInstance $event)
    {
        (new WhatsappIntegration($event->whatsappInstance))->createInstance();
    }
}

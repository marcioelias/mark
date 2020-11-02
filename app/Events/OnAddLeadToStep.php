<?php

namespace App\Events;

use App\Models\User\FunnelStepLead;
use App\Models\User\Postback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnAddLeadToStep
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postback;
    public $funnelStepLead;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Postback $postback, FunnelStepLead $funnelStepLead)
    {
        $this->postback = $postback;
        $this->funnelStepLead = $funnelStepLead;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //
    }
}

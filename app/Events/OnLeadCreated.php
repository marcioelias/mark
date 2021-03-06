<?php

namespace App\Events;

use App\Models\User\Postback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnLeadCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postback;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Postback $postback)
    {
        $this->postback = $postback;
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

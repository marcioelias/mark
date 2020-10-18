<?php

namespace App\Events;

use App\Models\User\Schedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $schedule;
    public $notificationData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule, string $notificationData)
    {
        $this->schedule = $schedule;
        $this->notificationData = $notificationData;
        // Log::info('Event: NotificationSent');
        // Log::debug($schedule);
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

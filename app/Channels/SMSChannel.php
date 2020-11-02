<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class SMSChannel {
    public function send($notifiable, Notification $notification) {
        $message = $notification->toSMS($notifiable);

        $to = $notifiable->routeNotificationFor('SMS');
    }
}

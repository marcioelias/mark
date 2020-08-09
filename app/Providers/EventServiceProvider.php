<?php

namespace App\Providers;

use App\Events\LeadGoToStep;
use App\Events\NewLeadCreated;
use App\Events\NewRunnableAction;
use App\Events\NotificationSent;
use App\Events\SetLeadTag;
use App\Listeners\AddLeadToFunnel;
use App\Listeners\AddSchedulesForStepActions;
use App\Listeners\DispatchNotificationAction;
use App\Listeners\ProcessRuleTags;
use App\Listeners\SetNotificationDone;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewLeadCreated::class => [
            ProcessRuleTags::class,
        ],
        SetLeadTag::class => [
            AddLeadToFunnel::class,
        ],
        LeadGoToStep::class => [
            AddSchedulesForStepActions::class,
        ],
        NewRunnableAction::class => [
            DispatchNotificationAction::class,
        ],
        NotificationSent::class => [
            SetNotificationDone::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

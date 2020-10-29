<?php

namespace App\Providers;

use App\Events\LeadGoToStep;
use App\Events\NewLeadCreated;
use App\Events\NewRunnableAction;
use App\Events\NotificationSent;
use App\Events\OnAddLeadToStep;
use App\Events\OnCreateWhatsappInstance;
use App\Events\OnLeadCreated;
use App\Events\OnLeadUpdated;
use App\Events\SetLeadTag;
use App\Listeners\AddLeadToFunnel;
use App\Listeners\AddSchedulesForStepActions;
use App\Listeners\DispatchNotificationAction;
use App\Listeners\DoOnAddLeadToStep;
use App\Listeners\DoOnCreateWhatsappInstance;
use App\Listeners\DoOnLeadCreated;
use App\Listeners\DoOnLeadUpdated;
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
        OnLeadCreated::class => [
            DoOnLeadCreated::class,
        ],
        OnLeadUpdated::class => [
            DoOnLeadUpdated::class,
        ],
        OnAddLeadToStep::class => [
            DoOnAddLeadToStep::class,
        ],
        /* NewLeadCreated::class => [
            ProcessRuleTags::class,
        ],
        SetLeadTag::class => [
            AddLeadToFunnel::class,
        ], */
        LeadGoToStep::class => [
            AddSchedulesForStepActions::class,
        ],
        NewRunnableAction::class => [
            DispatchNotificationAction::class,
        ],
        NotificationSent::class => [
            SetNotificationDone::class,
        ],
        OnCreateWhatsappInstance::class => [
            DoOnCreateWhatsappInstance::class,
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

<?php

namespace App\Providers;

use App\Events\LeadGoToStep;
use App\Events\NewRunnableAction;
use App\Events\NotificationSent;
use App\Events\OnAddLeadToStep;
use App\Events\OnCompleteCheckout;
use App\Events\OnCreateWhatsappInstance;
use App\Events\OnLeadCreated;
use App\Events\OnLeadUpdated;
use App\Events\OnMercadoPagoPaymentReceived;
use App\Listeners\AddSchedulesForStepActions;
use App\Listeners\DoOnAddLeadToStep;
use App\Listeners\DoOnCompleteCheckout;
use App\Listeners\DoOnCreateWhatsappInstance;
use App\Listeners\DoOnLeadCreated;
use App\Listeners\DoOnLeadUpdated;
use App\Listeners\DoOnMercadoPagoPaymentReceived;
use App\Listeners\DoOnNewRunnableAction;
use App\Listeners\SetNotificationDone;
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
        LeadGoToStep::class => [
            AddSchedulesForStepActions::class,
        ],
        NewRunnableAction::class => [
            DoOnNewRunnableAction::class,
        ],
        NotificationSent::class => [
            SetNotificationDone::class,
        ],
        OnCreateWhatsappInstance::class => [
            DoOnCreateWhatsappInstance::class,
        ],
        OnCompleteCheckout::class => [
            DoOnCompleteCheckout::class,
        ],
        OnMercadoPagoPaymentReceived::class => [
            DoOnMercadoPagoPaymentReceived::class,
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

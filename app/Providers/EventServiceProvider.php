<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\putUserCredentials',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\SetReloadCredentials',
            'App\Listeners\forgetUserCredentials',
        ],

        'App\Events\NewAppointmentWasScheduled' => [
            'App\Listeners\SendAppointmentNotificationToParties',
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}

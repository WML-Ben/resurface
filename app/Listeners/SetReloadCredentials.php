<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetReloadCredentials
{

    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        $event->user->reload_credentials = true;
        $event->user->save();
    }
}

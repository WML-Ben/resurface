<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class forgetUserCredentials
{

    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        session()->forget('credentials');
    }
}

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class putUserCredentials
{

    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        session()->put('credentials', auth()->user()->fetchCredentials());
    }
}

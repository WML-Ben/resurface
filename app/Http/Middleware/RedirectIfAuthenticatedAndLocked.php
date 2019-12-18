<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedAndLocked
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && session()->get('lockout') === true) {
            return redirect()->route('lockout')->withError('This session is locked.');
        }

        return $next($request);
    }
}

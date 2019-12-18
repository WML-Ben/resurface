<?php namespace App\Http\Middleware;

use Closure;

class RedirectIfNotAnAdmin
{

    public function handle($request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return redirect('/');
        }

        return $next($request);
    }

}

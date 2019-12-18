<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotABackendUser
{

	public function handle($request, Closure $next)
	{
		$user = $request->user();

		if ( ! $user) {
			return redirect()->route('login');
        }

        if (! $user->isBackend()) {
            return redirect('/');
        }

		return $next($request);
	}

}

<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Routing\Middleware\ThrottleRequests;

class PreventThrottle extends ThrottleRequests {

	protected $decayMinutes;

	public function handle($request, Closure $next, $maxAttempts = 3, $decayMinutes = 1)
	{
        $this->decayMinutes = $decayMinutes;

		return parent::handle($request, $next, $maxAttempts, $decayMinutes);
	}

	// override original method so we can redirect to some desired route:

	protected function buildResponse($key, $maxAttempts)
	{
        $response = [
            'success' => false,
            'message' => 'Too Many Attempts. Try again in ' . $this->decayMinutes . ' minute' . ($this->decayMinutes > 1 ? 's.' : '.'),
        ];

        return response()->json($response);
	}

}

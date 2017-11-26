<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;

class APIThrottler extends ThrottleRequests {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  int|string $maxAttempts
     * @param  float|int $decayMinutes
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1) {
        if ($request->ajax()) {
            return $next($request);
        } else {
            return parent::handle($request, $next, $maxAttempts, $decayMinutes);
        }
    }
}

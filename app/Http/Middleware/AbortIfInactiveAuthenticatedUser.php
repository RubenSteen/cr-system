<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbortIfInactiveAuthenticatedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && ! Auth::user()->isActive()) {
            Auth::logout();

            return redirect()->route('login')->with('warning', 'Your account has been set to inactive, you have been logged out.');
        }

        return $next($request);
    }
}

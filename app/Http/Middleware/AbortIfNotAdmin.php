<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AbortIfNotAdmin
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
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        return abort(403, 'Forbidden');
    }
}

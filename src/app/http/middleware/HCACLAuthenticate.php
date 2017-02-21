<?php

namespace interactivesolutions\honeycombacl\http\middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HCACLAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(env('AUTH_REDIRECT', 'auth/login'));
            }
        }

        return $next($request);
    }
}
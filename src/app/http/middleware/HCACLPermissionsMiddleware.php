<?php

namespace interaktyvussprendimai\ocv3acl\http\middleware;

use Closure;
use HCLog;

class HCACLPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $permission
     * @return mixed
     */
    public function handle ($request, Closure $next, $permission = null)
    {
        if (count ($request->segments ()) == 1 && $request->segment (1) == 'admin')
            $access = true;
        else
            $access = $request->user ()->can ($permission);

        if (!$access)
            abort(401);

        return $next($request);
    }
}
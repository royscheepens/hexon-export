<?php

namespace RoyScheepens\HexonExport\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyIpWhitelist
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $whitelist = app('config')->get('hexon-export.ip_whitelist');

        if(\App::environment('production') )
        {
            if(count($whitelist) && ! in_array($request->ip(), $whitelist))
            {
                abort(403, 'You are not allowed to access this resource.');
            }
        }

        return $next($request);
    }
}
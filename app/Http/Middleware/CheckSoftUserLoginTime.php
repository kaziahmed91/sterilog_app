<?php

namespace App\Http\Middleware;

use Closure;

class CheckSoftUserLoginTime
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
        $currentlyLoggedIn = $request->session()->get('softUser_userName');

        if (!is_null($currentlyLoggedIn) || $currentlyLoggedIn !== ''){
            // Session::set('lastActive', date('U'));
            $allowedTimeInSeconds = 600;
            
            $loggedInTime = $request->session()->get('softUser_startTime');

            $softUser_lastActive = $request->session()->get('softUser_lastActive');

            if ($loggedInTime && time() - $softUser_lastActive  > $allowedTimeInSeconds)
            {
                $request->session()->forget(['softUser_fullName', 'softUser_userName', 'softUser_startTime']);
            }
            $request->session()->put('softUser_lastActive', time());

        }

        return $next($request);
    }
}

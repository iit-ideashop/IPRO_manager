<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;
use Redirect;
use Route;

class IITUser
{
        /**
         * Handle an incoming request and allow if the user is authenticated as an IIT user.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
                if(!Auth::check()){
                        //Save the route we are trying to access
                        Session::put('routing.intended.parameters',Route::getCurrentRoute()->parameters());
                        Session::put('routing.intended.route',Route::getCurrentRoute()->getName());
                        return Redirect::route('authenticate');
                }

                return $next($request);
        }
}

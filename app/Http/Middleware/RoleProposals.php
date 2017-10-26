<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use User;
use Session;
use Route;
use Redirect;

class RoleProposals
{
        /**
         * Handle an incoming request and allow if the user has proposals rights.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
                if(Auth::check()){
                        //User is logged in
                        //If user is an admin or has the ROLE_PRINTING then we allow access
                        if((!Auth::user()->isAdmin) && (!User::checkRole("ROLE_PROPOSALS"))){
                                return Redirect::to("dashboard");
                        }
                }else{
                        //User isn't even logged in, send to admin login page
                        //Save the route we are trying to access
                        Session::put('routing.intended.parameters',Route::getCurrentRoute()->parameters());
                        Session::put('routing.intended.route',Route::getCurrentRoute()->getName());
                        return Redirect::route('authenticate');
                }

                return $next($request);
        }
}

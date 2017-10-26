<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Auth;
use Session;
use Redirect;

class AuthAdmin
{
        /**
         * Handle an incoming request and enforce that the user is an admin
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
                if(Auth::check()){
                        //User is logged in
                        if(!Auth::user()->isAdmin){
                                return Redirect::to('/dashboard');
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

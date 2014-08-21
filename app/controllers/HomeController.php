<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}
        
        public function showHome(){
            return View::make('index');
        }
        
        public function showDashboard(){
            //Here we show the user the dashboard
            $orders = Order::where('PeopleID','=',Auth::id())->get();
            View::share('orders',$orders);
            return View::make("dashboard");
        }
        public function logout(){
            Auth::logout();
            return Redirect::to('/')->with('success',array('Successfully logged out'));
        }

}

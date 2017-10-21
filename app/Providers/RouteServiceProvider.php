<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = null;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
	/*
	|--------------------------------------------------------------------------
	| Application & Route Filters
	|--------------------------------------------------------------------------
	|
	| Below you will find the "before" and "after" events for the application
	| which may be used to do any work before or after a request into your
	| application. Here you may also register your custom route filters.
	|
	*/
    /*
	Route::filter('auth_admin', function(){
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
	});

	Route::filter('role_printer', function(){
	    if(Auth::check()){
		//User is logged in
		//If user is an admin or has the ROLE_PRINTING then we allow access
		if((!Auth::user()->isAdmin) && (!User::checkRole("ROLE_PRINTING"))){
		    return Redirect::to("dashboard");
		}
	    }else{
		//User isn't even logged in, send to admin login page
		//Save the route we are trying to access
		Session::put('routing.intended.parameters',Route::getCurrentRoute()->parameters());
		Session::put('routing.intended.route',Route::getCurrentRoute()->getName());
		return Redirect::route('authenticate');
	    }
	});

	Route::filter('role_proposals', function(){
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
	});

	Route::filter('iit_user', function(){
	    if(!Auth::check()){
		//Save the route we are trying to access
		Session::put('routing.intended.parameters',Route::getCurrentRoute()->parameters());
		Session::put('routing.intended.route',Route::getCurrentRoute()->getName());
		return Redirect::route('authenticate');
	    }
	});

	Route::filter("project_enrolled", function($route){
	    $project_id = $route->getParameter("projectid");
	    $project = Project::find($project_id);
	    if($project == null){
	       return Redirect::route('dashboard')->with("error",array("The specified project does not exist"));
	    }
	    //Now to check for access
	    if(!Auth::user()->isAdmin) {
		if (!$project->isEnrolled()) {
		    return Redirect::route('dashboard')->with("error", array("You are not enrolled in that project"));
		}
	    }
	    //Share details about the project with the view
	    View::share('class',$project);
	    $account = $project->Account()->get();
	    if($account->isEmpty()){
		$account = new Account;
		$account->ClassID = $project->id;
		$account->save();
	    }else{
		//Once we have the class we need to pull the budgets for the class
		$account = $account[0];//Grab only 1 account
	    }
	    View::share('account',$account);
	});

	Route::filter("project_instructor", function($route){
	    $project_id = $route->getParameter("projectid");
	    $project = Project::find($project_id);
	    if($project == null){
		return Redirect::route('dashboard')->with("error",array("The specified project does not exist"));
	    }
	    //Now to check for access
	    if(!Auth::user()->isAdmin) {
		if ($project->getAccessLevel() == 0) {//Not enrolled
		    return Redirect::route('dashboard')->with("error", array("You do not have sufficient privileges to view that page"));
		} elseif ($project->getAccessLevel() < 2) {
		    return Redirect::route('project.dashboard', $project->id)->with("error", array("You do not have sufficient privileges to view that page"));
		}
	    }
	});
*/
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
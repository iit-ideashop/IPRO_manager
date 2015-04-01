<?php

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

App::before(function($request)
{
    //In the before we have to run the functions required to generate the navigation bar and the
    //sidebar data
        if(Auth::check()){
        	//This returns a listing of projects 
            $projects = PeopleProject::where('UserID','=',Auth::id())->lists('ClassID');
            //Setup the returns 
            $returnArray = array();
            $user = Auth::user();
            View::share('user', $user);
            $returnArray['classes'] = array();
            if(sizeof($projects) > 0){
                //Get the classes the user is enrolled in
                $classes = Project::whereIn('id',$projects)->get();
                $returnArray['classes'] = $classes;
            }
            if(Auth::user()->isAdmin){
                $returnArray['admin'] = array(
                    array('route'=>'admin_budgets','text'=>'Budget Requests'),
                    array('route'=>'admin.orders','text'=>'Orders'),
                    array('route'=>'admin.projects','text'=>'Project Management'),
                    array('route'=>'admin.iproday', 'text'=>'IPRO Day Management'),
                );
            }
            View::share('navigation',$returnArray);
        }else{
        }
});


App::after(function($request, $response)
{

});



/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

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
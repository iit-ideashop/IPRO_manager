<?php

namespace App\Http\Middleware;

use Closure;
use Project;
use Auth;
use Redirect;
use Account;

class ProjectEnrolled
{
        /**
         * Handle an incoming request and populate parameters related to an enrolled project, if the user has access.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
                $project_id = $request->route("projectid");
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
                View()->share('class',$project);
                $account = $project->Account()->get();
                if($account->isEmpty()){
                        $account = new Account;
                        $account->ClassID = $project->id;
                        $account->save();
                }else{
                        //Once we have the class we need to pull the budgets for the class
                        $account = $account[0];//Grab only 1 account
                }
                View()->share('account',$account);

                return $next($request);
        }
}

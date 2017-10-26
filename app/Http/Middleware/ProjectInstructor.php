<?php

namespace App\Http\Middleware;

use Closure;
use Project;
use Redirect;
use Auth;

class ProjectInstructor
{
        /**
         * Handle an incoming request and permit if the user is an authenticated instructor
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
                        if ($project->getAccessLevel() == 0) {//Not enrolled
                                return Redirect::route('dashboard')->with("error", array("You do not have sufficient privileges to view that page"));
                        } elseif ($project->getAccessLevel() < 2) {
                                return Redirect::route('project.dashboard', $project->id)->with("error", array("You do not have sufficient privileges to view that page"));
                        }
                }

                return $next($request);
        }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use View;
use Semester;
use PeopleProject;
use Project;
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view){
        //In the before we have to run the functions required to generate the navigation bar and the
                //sidebar data
                if(Auth::check()){
                        //Pull the active Semester
                        $activeSemester = Semester::where("Active","=","1")->first();
                        $view->with("activeSemester", $activeSemester);
                        //This returns a listing of projects
                        $projects = PeopleProject::where('UserID','=',Auth::id())->pluck('ClassID');
                        //Setup the returns 
                        $returnArray = array();
                        $user = Auth::user();
                        $view->with('user', $user);
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
                        //Check for printshop link
                        if((Auth::user()->checkRole("ROLE_PRINTING")) || (Auth::user()->isAdmin)){
                                if(array_key_exists("admin", $returnArray)){
                                        array_push($returnArray["admin"], array('route'=>'printing','text'=>'Printing Management'));
                                }else{
                                        $returnArray['admin'] = array(
                                                        array('route'=>'printing','text'=>'Printing Management')
                                                        );
                                }
                        }
                        //Check for proposals link, removed since proposal management has not been coded yet.
                        if((Auth::user()->checkRole("ROLE_PROPOSALS")) || (Auth::user()->isAdmin)){
                                if(array_key_exists("admin", $returnArray)){
                                        //array_push($returnArray["admin"], array('route'=>'proposals','text'=>'Proposal Management'));
                                }else{
                                        //$returnArray['admin'] = array(
                                        //array('route'=>'proposals','text'=>'Proposal Management'));
                                }
                        }


                        $view->with('navigation',$returnArray);
                }
       
    });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

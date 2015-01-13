<?php

class AdminProjectController extends BaseController{
    
    public function index($id = null){
        //Here we have to pull the current semester and view the projects for it
        $semesters  = Semester::all();
        $selectedSemester = null;
        if($id == null){
            $selectedSemester = Semester::where('active','=',1)->get();
            if($selectedSemester->isEmpty()){
                $selectedSemester = new Semester();
            }else{
                $selectedSemester = $selectedSemester[0];
            }
            }else{
            //We are loading certain projects
            $selectedSemester = Semester::find($id);
        }
        //Next we need to pull the projects for the active semester
        $projects = $selectedSemester->Projects()->get();
        View::share('semesters',$semesters);
        View::share('activeSemester',$selectedSemester);
        View::share('projects',$projects);
        return View::make('admin.projects.index');
    }
    
    public function view($semesterid){
        
    }
    
    //Get route
    public function create(){
        //Function to create a new project
        //Pull a semester and project list for the 
        $semesters = Semester::all();
        $projects = Project::all()->toArray();
        $nullProject = new Project();
        $nullProject->id = "null";
        $nullProject->UID = 'None';
        array_unshift($projects, $nullProject);
        View::share('semesters',$semesters);
        View::share('projects',$projects);
        return View::make('admin.projects.create');
        
        
    }
    
    //Post route
    public function createProcess(){
        $project = new Project;
        $project->UID = Input::get('UID');
        $project->Name = Input::get('Name');
        $project->Description = Input::get('Description');
        $project->TimeSlots = 'null';
        $project->Semester = Input::get('Semester');
        
        if(Input::get('ParentClass') == 'null'){
            $project->ParentClass = null;
        }else{
            $project->ParentClass = Input::get('ParentClass');
        }
        $project->modifiedBy = Auth::id();
        if($project->save()){
            //Save success!
            //Create account
            $account = new Account;
            $account->ClassID = $project->id;
            $account->save();
            $account->Deposit('BUDGET',floatval(str_replace('$','',Input::get('Budget'))));
            return Redirect::to('/admin/projects')->with('message','Project Added Successfully');
        }else{
            //We got errors
            return Redirect::to('/admin/projects/new')->with('errors',$project->errors()->all());
        }
    }
    
    public function edit($id){
        $project = Project::find($id);
        View::share('editProject',$project);
        $semesters = Semester::all();
        $projects = Project::all()->toArray();
        $nullProject = new Project();
        $nullProject->id = "null";
        $nullProject->UID = 'None';
        array_unshift($projects, $nullProject);
        View::share('semesters',$semesters);
        View::share('projects',$projects);
        return View::make('admin.projects.edit');
    }
    
    public function editProcess(){
        
    }
    
    
    public function overview($iproid){
        //Pull the project from the DB
        $project = Project::find($iproid);
        View::share('project',$project);
        //From the project we need to pull some data.
        //Budgets
        $budgets = $project->Budgets()->get();
        View::share('budgets',$budgets);
        //Budget requests
        $budgetReq = $project->BudgetRequests()->get();
        View::share('budgetRequests',$budgetReq);
        //Account
        $acct = $project->Account()->get();
        View::share('account',$acct);
        //Students 
        $students = $project->Users()->get();
        View::share('students',$students);
        return View::make('admin.projects.overview');
    }

    function enrollUsers($projectid){
        //In this function we will show a hands on table to enroll users
        //Look up the project so we can update the enrollment
        $project = Project::find($projectid);
        if(!$project){
            return Redirect::route('')->with('error',array('could not locate project'));
        }
        //Pass the project on to the view
        View::share('project',$project);
        //Get the enrollment
        $students = $project->Users()->get();
        View::share("students",$students);
        return View::make('admin.projects.enrollUsers');

    }
    
}


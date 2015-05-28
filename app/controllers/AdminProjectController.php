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
        $project->CRN = Input::get('CRN');
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
        $acct = $project->Account()->first();
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

    function uploadCognos($semester_id){
        //Built the upload cognos report page
        //Grab the semester from the DB so we can use the semester data on the page
        $semester = Semester::find(intval($semester_id));
        if($semester==null){
            return Redirect::route('admin.projects')->with('error',array('Provided semester does not exist'));
        }
        View::share('semester',$semester);
        return View::make('admin.projects.uploadCognos');
    }

    function uploadCognosProcess($semester_id){
        //Ok so we have a cognos report and an initial budget we should be assigning.
        //Let's see if the file has uploaded
        $semester = Semester::find(intval($semester_id));

        if($semester == null){
            return Redirect::route('admin.projects')->with('error',array('The provided semester does not exist'));
        }
        $cognosFile = Input::file('cognosFile');
        if($cognosFile == null){
            return Redirect::route('admin.projects.uploadCognos',$semester->id)->with('error',array('Please select a cognos report to upload'));
        }
        $initialBudget = floatval(str_replace('$','',Input::get('initialBudget')));;
        if($cognosFile->getClientOriginalExtension() != 'xlsx'){
            return Redirect::route('admin.projects.uploadCognos',$semester->id)->with('error',array('Please make sure you are uploading the correct cognos report in .xlsx format'));
        }
        //Let's take that uploaded file and run it through php excel.
        $excelReader = PHPExcel_IOFactory::createReader('Excel2007');
        $readerObject = $excelReader->load($cognosFile->getRealPath());
        //Quick check to make sure we have the right file
        if($readerObject->getActiveSheet()->getCell('A1')->getValue() != 'Class List by Campus'){
            return Redirect::route('admin.projects.uploadCognos',$semester->id)->with('error',array('Please upload the correct cognos report. It starts with "Class List by Campus'));
        }
        $log_array = array();
        //Alright, let's process this file and load this into the database.
        //Read each row and start building some sort of data structure.
        $start_row = 4;//Data starts at row 4
        $total_row = count($readerObject->getActiveSheet()->toArray());
        $spreadsheet_data = array();
        $currentCRN = null;
        for($current_row = $start_row; $current_row <= $total_row; $current_row++) {
            //Row data is as follows: (Relevant data only)
            //A is Academic period, Ex: Spring 2015 is 201520, works like Fiscal years so Fall 2014 would have been 201510, last digits increase by 10 for each sem. Fall 2015 will be 201610 etc.
            //B is Course Registration Number
            //C is subject, should always be IPRO unless it changes in the future
            //D is Course numbrer, ex. 497
            //E is Section, ex 303
            //F is campus
            //G is registration status. RW means registered. WL is waitlisted
            //H is the Student ID
            //I is the student name in Last, First comma delimted
            //J is the program of study
            //K is the student's email. Should be @hawk.iit.edu
            //Spreadsheet data will be organizaed as follows
            //['crn'] = array([0] = 'Project UID', [1] = array(array([0]="Student first name", [1] = "Student Last Name",[2]="StudentID",[3]="StudentEmail")));
            //So to get the Email we would check ['crn'][1][i][2]; We can loop to check for emails already existing. At this point we can also check if the students have an account in ipromanager?
            //Looping from here on out.
            //Checking for a change in CRN
            if ($readerObject->getActiveSheet()->getCell('B' . $current_row)->getValue() != '') {
                $currentCRN = $readerObject->getActiveSheet()->getCell('B' . $current_row)->getValue();
            }
            //Only run the code if the student is registered
            if ($readerObject->getActiveSheet()->getCell('G' . $current_row)->getValue() == 'RW') {
                //Start by checking $spreadsheet_data to see if the project exists by crn
                if (array_key_exists($currentCRN, $spreadsheet_data)) {
                    //Key exists, check the data inside for a duplicate student
                    $checkEmail = $readerObject->getActiveSheet()->getCell('K' . $current_row)->getValue();
                    $matchfound = false;
                    $arraylen = sizeof($spreadsheet_data[$currentCRN][1]);
                    for ($i = 0; $i < $arraylen; $i++) {
                        //Looping through the people array
                        if ($spreadsheet_data[$currentCRN][1][$i][3] == $checkEmail) {
                            $matchfound = true;
                        }
                    }
                    if (!$matchfound) {
                        //Add the student
                        $fullName = explode(",", $readerObject->getActiveSheet()->getCell('I' . $current_row)->getValue());
                        $studentObj = array($fullName[1], $fullName[0], $readerObject->getActiveSheet()->getCell('H' . $current_row)->getValue(), $readerObject->getActiveSheet()->getCell('K' . $current_row)->getValue());
                        array_push($spreadsheet_data[$currentCRN][1], $studentObj);
                    }

                } else {
                    //Key does not exist create the first entry and add the student
                    $spreadsheet_data[$currentCRN] = array();
                    $spreadsheet_data[$currentCRN][0] = $readerObject->getActiveSheet()->getCell('C' . $current_row)->getValue() . ' ' . $readerObject->getActiveSheet()->getCell('D' . $current_row)->getValue() . '-' . $readerObject->getActiveSheet()->getCell('E' . $current_row)->getValue();
                    $spreadsheet_data[$currentCRN][1] = array();
                    $fullName = explode(",", $readerObject->getActiveSheet()->getCell('I' . $current_row)->getValue());
                    $studentObj = array($fullName[1], $fullName[0], $readerObject->getActiveSheet()->getCell('H' . $current_row)->getValue(), $readerObject->getActiveSheet()->getCell('K' . $current_row)->getValue());
                    array_push($spreadsheet_data[$currentCRN][1], $studentObj);
                }
            }
        }
        //Spreadsheet data accurately represents the data that is supposed to be present in the db. Now we have to pull people from the database and build a similar structure.
        $database_data = array();
        //Grab the projects from the database for this semester
        $projects = $semester->Projects()->get();
        //So we have all the projects in the database now we need to loop through the projects and do some work on each one.
        foreach($projects as $project){
            //$project is a single database project
            $database_data[$project->CRN] = array();
            $students = $project->Users()->get();
            $database_data[$project->CRN][0] = $project->UID;
            $database_data[$project->CRN][1] = array();
            foreach($students as $student){
                $studentObj = array($student->FirstName, $student->LastName,$student->CWIDHash,$student->Email);
                array_push($database_data[$project->CRN][1], $studentObj);
            }
        }
        //We have built both spreadsheet databases
        //Next we must run a compare to find out who needs to be registered.
        //To do this we consult the spreadsheet and comapre it to the database.
        foreach($spreadsheet_data as $courseCRN => $spreadsheetLine){

            //We are looping through each course in the spreadsheet
            //Check if the course exists in the database
            $course = null;
            if(!array_key_exists($courseCRN, $database_data)){
                //Oh no, this course is not in the database yet! We can add the course to the DB

                $course = new Project;
                $course->UID = $spreadsheetLine[0];
                $course->Name = $spreadsheetLine[0];
                $course->Description = $spreadsheetLine[0];
                $course->CRN = $courseCRN;
                $course->Semester = $semester->id;
                $course->save();
                array_push($log_array, "Created ".$course->UID);
                    $account = new Account;
                    $account->ClassID = $course->id;
                    $account->Balance = 0;
                    $account->save();
                    array_push($log_array, "Created Account for ".$course->UID);
                //Let's check if we need to grant initial budgets
                if($initialBudget != 0){
                    //Grant some sort of initial budget
                    $budget = new Budget;
                    $budget->AccountID = $account->id;
                    $budget->Amount = $initialBudget;
                    $budget->Terms = 'Initial Project Funding';
                    $budget->Comment = 'Initial Project Funding';
                    $budget->Requester = 1;
                    $budget->Approver = 1;
                    $budget->save();
                    array_push($log_array, "Created Budget of $".$initialBudget." for ".$course->UID);
                    $account->Deposit("BUDGET",$initialBudget);

                }
            }else{
                $course = Project::where("CRN",'=',$courseCRN)->first();
            }

            //Next we loop through the students and enroll them in the class
            //loop through each spreadsheet line[1]
            for($i = 0; $i < count($spreadsheetLine[1]); $i++){
                //We are looping through each line of the students.
                //$spreadsheetLine[1][$i][0] is First name
                //$spreadsheetLine[1][$i][1] is Last name
                //$spreadsheetLine[1][$i][2] is CWID
                //$spreadsheetLine[1][$i][3] is email
                $emailFound = false;
                $email_search = $spreadsheetLine[1][$i][3];
                //loop through the crn db and find new people
                if(array_key_exists($courseCRN,$database_data)){
                   for ($j = 0; $j < count($database_data[$courseCRN][1]); $j++) {
                        if ($spreadsheetLine[1][$i][3] == $database_data[$courseCRN][1][$j][3]) {
                            $emailFound = true;
                        }
                    }
                }
                if(!$emailFound){
                    //Could not find the person in the DB. lets register them for this course
                    //First check if they are even in the db.
                    $user = User::where('Email','=',$email_search)->first();
                    if($user == null){
                        //We need to make the user
                        $user = new User;
                        $user->FirstName = $spreadsheetLine[1][$i][0];
                        $user->LastName = $spreadsheetLine[1][$i][1];
                        $user->CWIDHash = md5($spreadsheetLine[1][$i][2]);
                        $user->Email = $spreadsheetLine[1][$i][3];
                        $user->modifiedBy = "SYSTEM";
                        $user->save();
                    }
                    //The user exists in some way shape or form
                    //Enroll in the course
                    $peopleProject = new PeopleProject;
                    $peopleProject->UserID = $user->id;
                    $peopleProject->ClassID = $course->id;
                    $peopleProject->AccessType = 1;
                    $peopleProject->save();
                    array_push($log_array, "Added ".$user->FirsrName." ".$user->LastName."(".$user->Email.") to ".$course->UID);
                    Mail::send('emails.project.registered', array('person'=>$user,'project'=>$course), function($message) use($user){
                        $message->to($user->Email,$user->FirstName.' '.$user->LastName)->subject('Welcome to IPRO Manager!');
                    });
                }
            }
        }
        //Since we just applied an enroll we need to pull the database again
        $database_data = array();
        //Grab the projects from the database for this semester
        $projects = $semester->Projects()->get();
        //So we have all the projects in the database now we need to loop through the projects and do some work on each one.
        foreach($projects as $project){
            //$project is a single database project
            $database_data[$project->CRN] = array();
            $students = $project->Users()->get();
            $database_data[$project->CRN][0] = $project->UID;
            $database_data[$project->CRN][1] = array();
            foreach($students as $student){
                echo $student->AccessType;
                $studentObj = array($student->FirstName, $student->LastName,$student->CWIDHash,$student->Email);
                array_push($database_data[$project->CRN][1], $studentObj);
            }
        }
        //Done applying enroll, now we apply drop
        foreach($database_data as $courseCRN => $databaseLine) {
            //Find people in the database that are not on the spreadsheet
            $course = Project::where("CRN", '=', $courseCRN)->first();
            if (array_key_exists($courseCRN, $spreadsheet_data)) {
                //The crn is on the spreadsheet, lets check this data
                //now let's loop the $database line
                for ($i = 0; $i < count($databaseLine[1]); $i++) {
                    //This is each person in the db being looped. check if they are on the spreadsheet
                    $onSheet = false;
                    for ($j = 0; $j < count($spreadsheet_data[$courseCRN][1]); $j++) {
                        if ($databaseLine[1][$i][3] == $spreadsheet_data[$courseCRN][1][$j][3]) {
                            $onSheet = true;
                        }
                    }
                    if (!$onSheet) {
                        //Remove the registration from the database
                        $user = User::where("Email", '=', $databaseLine[1][$i][3])->first();
                        $registration = PeopleProject::where('ClassID', '=', $course->id)->where("UserID", '=', $user->id)->first();
                        $registration->delete();
                        array_push($log_array, "Dropped ".$user->FirsrName." ".$user->LastName."(".$user->Email.") from ".$course->UID);
                        Mail::send('emails.project.dropped', array('person'=>$user,'project'=>$course), function($message) use($user){
                            $message->to($user->Email,$user->FirstName.' '.$user->LastName)->subject('IPRO Manager update!');
                        });
                    }
                }
            }
        }
        $user = Auth::user();
        $admins = User::Where("isAdmin","=",true)->get();
        Mail::send('emails.log', array('person'=>$user,'reportType'=>'Cognos Upload','extraData'=>$semester->Name,'logdata'=>$log_array), function($message) use($admins){
            foreach($admins as $admin){
                $message->to($admin->Email,$admin->FirstName.' '.$admin->LastName);
            }
        $message->subject('IPRO Manager log created!');
        });
        return Redirect::route('admin.projects',$semester->id)->with('success', array("Cognos report successfully imported"));
    }
}
<?php
//Controller takes care of all controller functions for the printing route
class PrintingController extends BaseController{

    //Based on the type of credentials the user has we will show a different set of processes and stuff to the user
    public function index(){
        //Route for admins = printing.awaitingApproval
        //Route for print role = printing.awaitingPrint
        //check for admin
        if(Auth::user()->isAdmin){
            //User is an admin
            return Redirect::route("printing.awaitingApproval");
        }else if(User::checkRole("ROLE_PRINTING")){
            //User is a printer, redirect to the printers route
            return Redirect::route("printing.awaitingPrint");
        }else{
            //User has no access to this page.
            return Redirect::route("dashboard");
        }
    }

    public function awaitingApproval(){
        //Pull down the files awaiting approval
        $files = PrintSubmission::where("status","=",2)->get();
        View::share("files",$files);
        return View::make('printing.awaitingApproval');
    }

    public function awaitingPrint(){
        //Pull down files awaiting print by the printer
        $files = PrintSubmission::where("status","=",3)->get();
        View::share("files",$files);
        return View::make('printing.awaitingPrint');
    }

    public function printed(){
        //Pull down files printed by the printer
        $files = PrintSubmission::where("status","=",4)->get();
        View::share("files",$files);
        return View::make('printing.printed');
    }

    public function checkInPosters(){
         $files = PrintSubmission::where("status","=",4)->get();
         View::share("files",$files);
         return View::make('printing.checkinposter');
    }

    public function posterPickup(){
        //Pull down files printed by the printer
        $files = PrintSubmission::where("status","=",5)->get();
        View::share("files",$files);
        return View::make('printing.posterPickup');
    }

    public function projectReport($projectid = null){
        //Check for the projectID if there was one submitted
        if(isset($projectid)){
            //Pull only data for a single project
            $project = Project::find($projectid);
            if($project == NULL){
                return Redirect::route("printing.projectReport")->with("error",array("The specified project does not exist"));
            }
            //Next we have to pull the print submissions for the project and display the project out
            $printSubmissions = PrintSubmission::where("ProjectID","=",$project->id)->get();
            View::share("project",$project);
            View::share("printSubmissions",$printSubmissions);
            return View::make("printing.projectReportIndividual");
        }
        //We need to pull down the projects for this semester
        $semester = Semester::where("Active","=","1")->first();
        $projects = Project::where("Semester","=",$semester->id)->get();
        //Convert to projectIDs for a WhereIn query
        $projectids = array();
        foreach($projects as $project){
            $projectids[] = $project->id;
        }
        //Now we pull print submissions that only belong to this semesters projects
        $printSubmissions = PrintSubmission::WhereIn("ProjectID",$projectids)->get();
        $printReportTotals = array();
        $printReportTotals["totalPosters"] = 0;
        $printReportTotals["awaitUserPosterApproval"] = 0;
        $printReportTotals["awaitAdminPosterApproval"] = 0;
        $printReportTotals["awaitPrint"] = 0;
        $printReportTotals["printed"] = 0;
        $printReportTotals["awaitingPickup"] = 0;
        $printReportTotals["pickedUp"] = 0;
        $printReportTotals["postersRejected"] = 0;
        //ok we have print submissions, now for some hardcore looping
        foreach($projects as $project){
            //Loop each project and add some extra info to it for the purposes of the view
            $project->totalPosters = 0;
            $project->awaitUserPosterApproval = 0;
            $project->awaitAdminPosterApproval = 0;
            $project->awaitPrint = 0;
            $project->printed = 0;
            $project->awaitingPickup = 0;
            $project->pickedUp = 0;
            $project->postersRejected = 0;
            foreach($printSubmissions as $printSubmission){
                if($printSubmission->ProjectID == $project->id){
                    switch($printSubmission->status){
                        case 1:
                            $project->awaitUserPosterApproval += 1;
                            $printReportTotals["awaitUserPosterApproval"] += 1;
                            break;
                        case 2:
                            $project->totalPosters += 1;
                            $printReportTotals["totalPosters"] += 1;
                            $project->awaitAdminPosterApproval += 1;
                            $printReportTotals["awaitAdminPosterApproval"] += 1;
                            break;
                        case 3:
                            $project->awaitPrint +=1;
                            $printReportTotals["awaitPrint"] += 1;
                            $project->totalPosters += 1;
                            $printReportTotals["totalPosters"] += 1;
                            break;
                        case 4:
                            $project->printed += 1;
                            $printReportTotals["printed"] += 1;
                            $project->totalPosters += 1;
                            $printReportTotals["totalPosters"] += 1;
                            break;
                        case 5:
                            $project->awaitingPickup += 1;
                            $printReportTotals["awaitingPickup"] += 1;
                            $project->totalPosters += 1;
                            $printReportTotals["totalPosters"] += 1;
                            break;
                        case 6:
                            $project->pickedUp += 1;
                            $printReportTotals["pickedUp"] += 1;
                            $project->totalPosters += 1;
                            $printReportTotals["totalPosters"] += 1;
                            break;
                        case 7:
                            $project->postersRejected += 1;
                            $printReportTotals["postersRejected"] += 1;
                            break;
                    }
                }
            }
        }
        View::share("projects",$projects);
        View::share("printReportTotals",$printReportTotals);
        return View::make("printing.projectReport");
    }

    public function downloadFile($fileid){
        //Check for auth of admin or printadmin or see if user is enrolled in the project to be allowed to download the file
        //Pull the file we are trying to download
        $fileName = Input::get("naming");
        if($fileName == null){
            $fileName = "default";
        }elseif($fileName == ""){
            $fileName = "default";
        }elseif($fileName != "job"){
            $fileName = "default";
        }
        $printSubmission = PrintSubmission::where("id","=",intval($fileid))->first();
        if($printSubmission == null){
            return Response::make("The requested file does not exist or has been deleted");
        }
        //Ok file exists, lets check for auth printer, admin or project member
        $project = Project::where("id","=",$printSubmission->ProjectID)->first();
        $authValidated = false;
        if(Auth::user()->isAdmin){
            $authValidated = true;
        }
        if(Auth::user()->checkRole("ROLE_PRINTING")){
            $authValidated = true;
        }
        if($project->isEnrolled()){
            $authValidated = true;
        }
        if($authValidated){
            if($fileName == "default"){
                return Response::download(Config::get("app.StorageURLs.printSubmissions").$printSubmission->filename,$printSubmission->original_filename);
            }elseif($fileName == "job"){
                return Response::download(Config::get("app.StorageURLs.printSubmissions").$printSubmission->filename,"Job-".$printSubmission->id.".pdf");
            }
        }else{
            return Response::make("You do not have access to download this file");
        }
    }

    public function viewFile($fileid){
        $printSubmission = PrintSubmission::where("id","=",intval($fileid))->first();
        if($printSubmission == null){
            return Response::make("The requested file does not exist or has been deleted");
        }
        //Ok file exists, lets check for auth printer, admin or project member
        $project = Project::where("id","=",$printSubmission->ProjectID)->first();
        $authValidated = false;
        if(Auth::user()->isAdmin){
            $authValidated = true;
        }
        if(Auth::user()->checkRole("ROLE_PRINTING")){
            $authValidated = true;
        }
        if($project->isEnrolled()){
            $authValidated = true;
        }
        if($authValidated) {
            $filename = Config::get("app.StorageURLs.printSubmissions") . $printSubmission->filename;  // <- Replace with the path to your .pdf file
            if (file_exists($filename)) {
                // create a Laravel Response with the file and the correct mimetype,
                //  and an array of html headers including the pdf content type
                return Response::file($filename, array('content-type' => 'application/pdf'));
            } else {
                return Response::make("You do not have access to view this file");
            }
        }
    }



    //***** The following functions are API functions used for JSON requests ******//
    public function approvePoster(){
        //This function allows an admin to approve or deny a file.
        $fileid = Input::get("fileid");
        //Pull the file from the DB
        $printSubmission = PrintSubmission::where("id","=",intval($fileid))->first();
        if($printSubmission == null){
            return Response::json(array("error"=>"Could not locate file in database"));
        }
        //File exists, make sure we are waiting for user input
        if($printSubmission->status != 2){
            return Response::json(array("error"=>"File not awaiting approval"));
        }
        $action = Input::get("action");

        //Depending on the action we will do different things to $fileid
        if($action == "Approve"){
            $printSubmission->status = 3;
            $printSubmission->save();
            return Response::json(array("success"=>"true","action"=>"Approve"));
        }elseif($action == "Deny"){
            $rejectComments = Input::get("rejectComment");
            unlink(Config::get("app.StorageURLs.printSubmissions").$printSubmission->filename);
            $printSubmission->reject_comments = $rejectComments;
            $printSubmission->status = 7;
            $printSubmission->save();
            $user = User::where("id","=",$printSubmission->UserID)->first();
            Mail::send('emails.printing.rejected', array('person'=>$user,'fileSubmission'=>$printSubmission), function($message) use($user, $printSubmission){
                $message->to($user->Email,$user->FirstName.' '.$user->LastName);
                $message->subject('Sorry, your '.$printSubmission->file_type.' : '.$printSubmission->original_filename.' has been rejected!');
            });
            return Response::json(array("success"=>"true","action"=>"Deny"));
        }else{
            //Not a supported action
            return Response::json(array("error"=>"Action type not supported"));
        }
    }

    public function markPrinted(){
        //This function allows an admin to approve or deny a file.
        $fileid = Input::get("fileid");
        //Pull the file from the DB
        $printSubmission = PrintSubmission::where("id","=",intval($fileid))->first();
        if($printSubmission == null){
            return Response::json(array("error"=>"Could not locate file in database"));
        }
        //File exists, make sure we are waiting for user input
        if(($printSubmission->status != 3) && ($printSubmission->status != 4)){
            return Response::json(array("error"=>"File not in print queue"));
        }
        $action = Input::get("action");

        //Depending on the action we will do different things to $fileid
        if($action == "Printed"){
            $printSubmission->status = 4;
            $printSubmission->save();
            return Response::json(array("success"=>"true","action"=>"Printed"));
        }elseif($action == "Undo"){
            $printSubmission->status = 3;
            $printSubmission->save();
            return Response::json(array("success"=>"true","action"=>"Undo"));
        }else{
            //Not a supported action
            return Response::json(array("error"=>"Action type not supported"));
        }
    }

    public function printBarcode($fileid){
        //We need to pull down the poster object.
        //get the file from db
        $printSubmission = PrintSubmission::where("id","=",$fileid)->first();
        if($printSubmission == null){
            return Response::make("Error location submission in the database.");
        }
        //Check if the poster already has a barcode generated for it
        if($printSubmission->barcode == NULL){
            //Generate a barcode
            $barcode = new Barcode;
            $barcode->type = "PRINT";
            $barcode->reference = $printSubmission->id;
            $barcode->save();
            $printSubmission->barcode = $barcode->id;
            $printSubmission->save();
        }

        //Poster exists, lets generate a barcode for it.
        View::share("printSubmission", $printSubmission);
        return View::make("printing.print_label");
    }

    public function receivePosterFromPrinter(){
        //This function allows an admin to approve or deny a file.
        $fileid = Input::get("fileid");
        //Pull the file from the DB
        $printSubmission = PrintSubmission::where("id","=",intval($fileid))->first();
        if($printSubmission == null){
            return Response::json(array("error"=>"Could not locate file in database"));
        }
        //File exists, make sure we are waiting for user input
        if($printSubmission->status != 4){
            return Response::json(array("error"=>"File not yet printed"));
        }
        //Take the poster and mark it the correct status
        $printSubmission->status = 5;
        $printSubmission->save();
        //Send out an email that poster is ready for pickup
        $user = User::where("id","=",$printSubmission->UserID)->first();
        Mail::send('emails.printing.printed', array('person'=>$user,'fileSubmission'=>$printSubmission), function($message) use($user, $printSubmission){
            $message->to($user->Email,$user->FirstName.' '.$user->LastName);
            $message->subject('Extra! Extra! '.$printSubmission->file_type.' : '.$printSubmission->original_filename.' has been printed!');
        });
        return Response::json(array("success"=>"true"));
   }

    function userSearch(){
        //This function will take the submitted ID#, find the user and redirect to the view orders page
        $studentID = strtoupper(Input::get('idnumber'));
        $studentFName = Input::get('firstName');
        $studentLName = Input::get('lastName');
        $studentEmail = Input::get('email');
        //Now let's find the user in the database who's ID number that is
        $student = User::where('CWIDHash','LIKE',md5($studentID))->orWhere('FirstName','LIKE',$studentFName)->orWhere('LastName','LIKE',$studentLName)->orWhere('Email','LIKE',$studentEmail)->get();
        if($student->isEmpty()){
            return Redirect::route('printing.posterpickup')->with('error',array('Could not find a user that matches your query'));
        }
        //Ok so we have atleast one user in our collection. If there is 1 user we can move onto the view pickup page
        //If the query gave us mutliple users we have to find out which user we want to perform a pickup for
        if($student->count() == 1){
            //Redirect to a different controller with the user info
            return Redirect::action('PrintingController@studentPosterPickup', array('userid' => $student[0]->id));
        }else{
            View::share('students',$student);
            return View::make('printing.searchResults');
        }
    }

    public function studentPosterPickup(){
        $userid = Input::get("userid");
        $student = User::find(intval($userid));
        if($student == null){
            return Redirect::route('printing.posterpickup')->with('error',array('The specified user does not exist, please try again'));
        }
        View::share('student',$student);
        //Student object passed
        //Find the user's prints, for now
        $activeSemester = Semester::where("Active","=","1")->first();
        $curTermProjects = Project::where("semester","=",$activeSemester->id)->pluck("id");
        $projectsEnrolled = PeopleProject::where("UserID","=",$student->id)->pluck("ClassID");
        //Find the intersection of projects that the user is enrolled in and this semesters projects
        $pickupProjects = $projectsEnrolled->intersect($curTermProjects)->toArray();

        //$printSubmissions = PrintSubmission::where("UserID","=",$student->id)->orWhereIn("ProjectID",$pickupProjects)->where("status","=","5")->where("barcode","!=","Null")->get();
        $printSubmissions = PrintSubmission::where(function ($query) use ($student) {
            $query->where('UserID', '=', $student->id)
                ->where('status', '=', 5)
                ->where("barcode","!=","Null");
        })->orWhere(function ($query) use ($pickupProjects){
            $query->whereIn('ProjectID',$pickupProjects)
                ->where('status', '=', 5)
                ->where("barcode","!=","Null");
        })->get();
        if($printSubmissions->isEmpty()){
            return Redirect::route('printing.posterpickup')->with('error',array('The specified user does not have anything to pickup'));
        }
        View::share("printSubmissions",$printSubmissions);
        return View::make("printing.viewPrints");
    }

    public function completeStudentPickup(){
        //This function will receive a JSON array of file id's
        $studentid = Input::get('pickupuserid');
        $PrintIDs = json_decode(Input::get('PrintIDs'));
        if(sizeof($PrintIDs) < 1){
            //We have 0 items in the array
            return Redirect::route('printing.pickup', array('userid' => $studentid))->with('error', array('No prints were marked for pickup. Try again'));
        }
        //Get the prints the student can pickup
        $activeSemester = Semester::where("Active","=","1")->first();
        $curTermProjects = Project::where("semester","=",$activeSemester->id)->pluck("id");
        $projectsEnrolled = PeopleProject::where("UserID","=",$studentid)->pluck("ClassID");
        //Find the intersection of projects that the user is enrolled in and this semesters projects
        $pickupProjects = $projectsEnrolled->intersect($curTermProjects)->toArray();

        //$printSubmissions = PrintSubmission::where("UserID","=",$student->id)->orWhereIn("ProjectID",$pickupProjects)->where("status","=","5")->where("barcode","!=","Null")->get();
        $printSubmissions = PrintSubmission::where(function ($query) use ($studentid) {
            $query->where('UserID', '=', $studentid)
                ->where('status', '=', 5)
                ->where("barcode","!=","Null");
        })->orWhere(function ($query) use ($pickupProjects){
            $query->whereIn('ProjectID',$pickupProjects)
                ->where('status', '=', 5)
                ->where("barcode","!=","Null");
        })->pluck("id");

        //Loop through each of the files and mark them as picked up by student if the student has access.
        //Pull all of the prints
        $alternate_user_pickup = array();
        $prints = PrintSubmission::whereIn("id",$PrintIDs)->get();
        foreach($prints as $print){
            //Check the pickup array
            foreach($printSubmissions as $printSubmission){
                if($print->id == $printSubmission){
                    $print->status = 6;
                    $print->pickup_UserID = $studentid;
                    if($print->UserID != $print->pickup_UserID){
                        array_push($alternate_user_pickup,$print);
                    }
                    $print->save();
                }
            }
        }
        //check for alternate pickups so we can send emails
        if(count($alternate_user_pickup) != 0){
            //We need to send some emails
            foreach($alternate_user_pickup as $alternatePosterPickup){
                $user = User::where("id","=",$alternatePosterPickup->UserID)->first();
                Mail::send('emails.printing.alternatePickup', array('person'=>$user,'fileSubmission'=>$alternatePosterPickup), function($message) use($user, $alternatePosterPickup){
                    $message->to($user->Email,$user->FirstName.' '.$user->LastName);
                    $message->subject($alternatePosterPickup->file_type.' : '.$alternatePosterPickup->original_filename.' has been picked up!');
                });
            }
        }
        return Redirect::route("printing.posterpickup")->with("success",array("Posters Successfully checked out!"));

    }

    public function projectReportIndividual($projectid){
        //Take the project and show all printing related to the project
        $printSubmissions = PrintSubmission::where("ProjectID","=",intval($projectid))->get();

        exit;
    }

}


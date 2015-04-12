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

    public function projectReport(){
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
        //ok we have print submissions, now for some hardcore looping
        foreach($projects as $project){
            //Loop each project and add some extra info to it for the purposes of the view
            $project->postersSubmitted = 0;
            foreach($printSubmissions as $printSubmission){
                if($printSubmission->ProjectID == $project->id){
                    $project->postersSubmitted += 1;
                }
            }
        }
        View::share("projects",$projects);
        return View::make("printing.projectReport");
    }

    public function downloadFile($fileid){
        //Check for auth of admin or printadmin or see if user is enrolled in the project to be allowed to download the file
        return Response::download(Config::get("app.StorageURLs.printSubmissions")."2_Poster.pdf","My_Little_Pony.pdf");
    }

    public function viewFile($fileid){
        $file = Config::get("app.StorageURLs.printSubmissions")."2_Poster.pdf";  // <- Replace with the path to your .pdf file
        // check if the file exists
        echo $file;
        if (file_exists($file)) {
            // read the file into a string
            $content = file_get_contents($file);
            // create a Laravel Response using the content string, an http response code of 200(OK),
            //  and an array of html headers including the pdf content type
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
        }else{
            return Response::make("File does not exist or is not available");
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
            return Redirect::route('admin.order.pickup')->with('error',array('The specified user does not exist, please try again'));
        }
        View::share('student',$student);
        //Student object passed
        //Find the user's prints, for now
        $printSubmissions = PrintSubmission::where("UserID","=",$student->id)->where("status","=","5")->where("barcode","!=","Null")->get();
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
        //Loop through each of the files and mark them as picked up by student.
        //Pull all of the prints
        $prints = PrintSubmission::whereIn("id",$PrintIDs)->get();
        foreach($prints as $print){
            $print->status = 6;
            $print->pickup_UserID = $studentid;
        }

    }

}


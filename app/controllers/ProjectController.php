<?php

class ProjectController extends BaseController{
    
    public function Index($id){
        $class = Project::find($id);
        //Let's get this projects account
        $account = $class->Account()->first();
        $orders = Order::where('ClassID','=',$class->id)->get();
        $budgets = Budget::where('AccountID','=',$account->id)->get();
        //Next it's budget requests
        $budget_requests = BudgetRequest::where('AccountID','=',$account->id)->get();
        
        //Pack the data
        View::share("orders",$orders);
        View::share("budgets",$budgets);
        View::share("budgetRequests",$budget_requests);
        return View::make('project.index');
    }

    //Show a page containing ipro orders
    public function showOrders($id){
        $class = Project::find($id);
        $orders = Order::where('ClassID','=',$class->id)->get();
        View::share('orders',$orders);
        return View::make('project.orders');

    }

    //Show a page with the class roster
    public function showRoster($id){
        $class = Project::find($id);
        $students = $class->Users()->get();
        View::share('students',$students);
        return View::make('project.roster');
    }

    //Show the faculty group manager to create smaller groups for the class
    public function groupManager($projectid){
        //The group manager page contains all the code required to show groups and make modifications via a simple api and ajax calls. Let's load the page and let
        //javascript handle things from this point on
        return View::make('project.groupManager');
    }

    //Show the print submission page for users
    public function printSubmission($projectid){

        return View::make("project.printSubmission");
    }

    public function printSubmissionUpload($projectid){
        //We already verified we are enrolled in the $projectid or we are admin.
        //We can create files with the $projectid easily.
        $fileSubmission = Input::file("fileUpload");
        $fileType = Input::get("fileType");
        if(!$fileSubmission->isValid()){
            $error_array = array();
            $error_array['error'] = "Error when uploading file. File is invalid";
            return Response::json($error_array);
        }
        //Take the file and verify it is a pdf
        if($fileSubmission->getMimeType() != "application/pdf"){
            $error_array = array();
            $error_array['error'] = "Error when uploading file. File must be a PDF";
            return Response::json($error_array);
        }
        //Next take the file and verify that it is less than 150mb
        if ($fileSubmission->getSize() > 150000000){
            $error_array = array();
            $error_array['error'] = "Error when uploading file. File is too large";
            return Response::json($error_array);
        }
        //Create a new printsubmission object in the database
        $printSubmission = new PrintSubmission();
        $printSubmission->UserID = Auth::id();
        $printSubmission->ProjectID = $projectid;
        $printSubmission->original_filename = $fileSubmission->getClientOriginalName();
        $printSubmission->size = number_format($fileSubmission->getSize() / 1000000, 2)." Mb";
        $printSubmission->count_copies = 1;
        $printSubmission->file_type = $fileType;
        $printSubmission->override = true;
        $printSubmission->status = 1;
        $printSubmission->save();
        $printSubmission->filename = $printSubmission->id."_".$printSubmission->file_type.".pdf";
        $printSubmission->save();
        //File is ok, lets save it so we can work with it
        //Take the file and move it to our secured location (Allows only downloads by owners, admins and people in the same ipro and print admins)
        $fileSubmission->move(Config::get("app.StorageURLs.printSubmissions"),$printSubmission->filename);
        //Next we need to verify the pdf dimensions are the correct dimensions
        $pdfinfo_output = shell_exec("pdfinfo ".Config::get("app.StorageURLs.printSubmissions").$printSubmission->filename);
        $pdfdata = explode("\n", $pdfinfo_output); //puts it into an array
        //Take pdfinfo output and figure out the dimensions in point
        $pdfdimensions_width = 0;
        $pdfdimensions_height = 0;
        for($c=0; $c < count($pdfdata); $c++) {
            if(stristr($pdfdata[$c],"Page size") == true) {
                $pdfdimensions = trim(substr($pdfdata[$c], strpos($pdfdata[$c], ":") + 1, strlen($pdfdata[$c])));
                $pdfdimensions = trim(substr($pdfdimensions, 0, strpos($pdfdimensions, "p"))); // width x height
                $pdfdimensions_width = trim(substr($pdfdimensions, 0, strpos($pdfdimensions, "x")));
                $pdfdimensions_height = trim(substr($pdfdimensions, strpos($pdfdimensions, "x") + 1, strlen($pdfdimensions)));
                $printSubmission->dimensions = ($pdfdimensions_width / 72) . " x " . ($pdfdimensions_height / 72);
            }
        }
        //Save the file if dimensions are ok and send student an email
        if($printSubmission->file_type == "Poster"){
            //Verify poster sizes
            $posterSizeArray = Config::get("app.approved_poster_sizes");
            for($i = 0; $i < count($posterSizeArray); $i++){
                if(($pdfdimensions_width == $posterSizeArray[$i]["width"]) && ($pdfdimensions_height == $posterSizeArray[$i]["height"])){
                    //We have a poster match
                    //Poster size approved!
                    $printSubmission->override = false;
                    $printSubmission->status = 2;
                    $i = count($posterSizeArray);
                }
            }
        }elseif($printSubmission->file_type == "Brochure"){
            //Verify brochure sizes
            $brochureSizeArray = Config::get("app.approved_brochure_sizes");
            for($i = 0; $i < count($brochureSizeArray); $i++){
                if(($pdfdimensions_width == $brochureSizeArray[$i]["width"]) && ($pdfdimensions_height == $brochureSizeArray[$i]["height"])){
                    //Approved size
                    $printSubmission->override = false;
                    $printSubmission->status = 2;
                    $i = count($brochureSizeArray);
                }
            }
        }
        $printSubmission->save();
        //Send the email or schedule an email to be dispatched
        if(!$printSubmission->override){
            //We will send an email if the file is ok.
            //We need to give it a $person object and a $filesubmission object
            $user = Auth::user();
            Mail::send('emails.printing.received', array('person'=>$user,'fileSubmission'=>$printSubmission), function($message) use($user, $printSubmission){
                $message->to($user->Email,$user->FirstName.' '.$user->LastName);
                $message->subject('Your '.$printSubmission->file_type.':'.$printSubmission->filename.' has been received!');
            });
        }

        //Take the file and return the filename or rather the file object
        $fileobject = array();
        $fileobject['filename'] = $printSubmission->original_filename;
        $fileobject['link'] = URL::route('printing.downloadfile',$printSubmission->id); // needs to be finished
        $fileobject['filesize'] = $printSubmission->size; // Filesize in megabytes, up to two decimal places
        $fileobject['dimensions'] = $printSubmission->dimensions;//file dimensions in inches
        $fileobject['uploaded_by'] = User::getFullNameWithId(Auth::id()); //Full name of person who uploaded the file
        $date_created = date('m/d/Y g:i a',$printSubmission->created_at->timestamp);
        $fileobject['upload_time'] = $date_created;//Timestamp the file was uploaded during
        if($printSubmission->status == 1){
            $fileobject['needs_override'] = true;
        }else{
            $fileobject['needs_override'] = false;
        }

        $fileobject['fileid'] = $printSubmission->id;
        $fileobject['textstatus'] = $printSubmission->getStatus();
        return Response::json($fileobject);
    }

    public function getProjectFiles($projectid){
        //We already know the student has access to this project via filters
        $files = PrintSubmission::where("ProjectID","=",$projectid)->get();
        //We need to return file objects
        $fileobjectarray = array();
        foreach($files as $file){
            $tmpFileObject = array();
            $tmpFileObject['filename'] = $file->original_filename;
            $tmpFileObject['link'] = URL::route('printing.downloadfile',$file->id);
            $tmpFileObject['filesize'] = $file->size;
            $tmpFileObject['dimensions'] = $file->dimensions;
            $tmpFileObject['uploaded_by'] = User::getFullNameWithId($file->UserID);
            $date_created = date('m/d/Y g:i a',$file->created_at->timestamp);
            $tmpFileObject['upload_time'] = $date_created;//Timestamp the file was uploaded during
            if($file->status == 1){
                $tmpFileObject['needs_override'] = true;
            }else{
                $tmpFileObject['needs_override'] = false;
            }
            $tmpFileObject['fileid'] = $file->id;
            $tmpFileObject['textstatus'] = $file->getStatus();
            array_push($fileobjectarray, $tmpFileObject);
        }
        return Response::json($fileobjectarray);
    }

    public function overridePrintSubmission($projectid){
        //This function will override a print submission and either approve the submission or will delete the file based on user needs.
    }


}


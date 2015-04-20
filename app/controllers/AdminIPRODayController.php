<?php
class AdminIPRODayController extends BaseController{
    
    function index(){
        //Show the initial iproday page with all of the ipro days as a drop down and their respective registrations.
        //Grab all the ipro days
        $iprodays = IPRODay::all();
        //Grab the most recent one
        $iproday_recent = IPRODay::all()->last();
        //next we have to pull the registration for the iproday
        $regRecords = Registration::leftJoin('registrants','registrant','=','registrants.id')->where('iproday','=',$iproday_recent->id)->get();
        $tracks = Track::where('iproday','=',$iproday_recent->id)->get();
        
        View::share('iprodays',$iprodays);
        View::share('iproday',$iproday_recent);
        View::share('reg_data',$regRecords);
        View::share('tracks',$tracks);
        return View::make('admin.iproday.index');
        
        
        
    }
    
    
    function reporting($id,$report){
        //This route is for iproday reporting
        //Let's make sure the report the user wants to generate exists
        $reports_installed = array('registration');
        $report_valid = false;
        //Make sure we can actually generate the report
        if(in_array($report,$reports_installed)){
            $report_valid = true;
        }else{
            return Response::make("Report does not exist or is not configured");
        }
        
        //Create a new spreadsheet in memory
        $objPHPExcel = new PHPExcel();
        //Set properties
        $objPHPExcel->getProperties()->setCreator("IPRO Manager");
        $objPHPExcel->getProperties()->setLastModifiedBy("IPRO Manager");
        $objPHPExcel->getProperties()->setTitle("IPRO Manager Report");
        $objPHPExcel->getProperties()->setSubject("IPRO Manager Report");
        $objPHPExcel->getProperties()->setDescription("Report generated by IPRO Manager");
        //once the file is generated we should hand off the phpexcel to a view and have the view create the data. views should be in a reports folder?
        View::share('objPHPExcel',$objPHPExcel);
        View::share('IPROID',$id);
        //Custom report configurations, if any
        return View::make('admin.reports.'.$report);
    }

    function peoplesChoice(){
        return View::make("admin.iproday.peoplesChoice");
    }
    function peoplesChoiceTerminal(){
        return View::make("admin.iproday.peoplesChoiceTerminal");
    }
<<<<<<< Updated upstream
=======

    //In this controller we will be taking input of FirstName, LastName and ID number
    public function api_validateUser(){
        $firstName = Input::get("firstName");
        $lastName = Input::get("lastName");
        $idnumber = Input::get("idnumber");
        $inputdata = array("firstName" => $firstName, "lastName"=>$lastName,"idnumber"=>$idnumber);
        //Some data for us
        $activeSemester = Semester::where("Active","=",true)->first();
        //We have to take the input and see if this user has already voted
        //We need to validate this user hasn't voted yet. Check the ID first
        $vote = PeoplesChoice::where("idnumber","=",$idnumber)->where("Semester","=",$activeSemester->id)->first();
        if($vote != null){
            return Response::json(array_merge($inputdata, array("app_error"=>"You have already voted. Everyone is allowed to vote only once")));
        }
        //Now it gets a bit more complicated. We have to see if the person has already voted with us by not using the terminal
        $vote = PeoplesChoice::where("FirstName","LIKE","%".$firstName."%")->orWhere("LastName","LIKE","%".$lastName."%")->where("Semester","=",$activeSemester->id)->first();
        if($vote != null){
            return Response::json(array_merge($inputdata, array("app_error"=>"There was an error with your entry. Please see the registration desk.")));
        }
        //Ok,so essentially by now we have checked to make sure someone hasnt voted twice or someone with the same name is voting twice, needs verification
        //Let's see if this is an ipro student
        $person = User::where("CWIDHash","=",md5($idnumber))->first();
        $blacklistedProjectIDs = array();
        if($person != null){
            //IPRO STUDENT!! Let's see if they are enrolled in anything this semester
            $enrollment = PeopleProject::where("UserID","=",$person->id)->lists("ClassID");
            $blacklistedProjectIDs = array_merge($enrollment,$blacklistedProjectIDs);
        }

        //Pull all the projects from this semester
        $projects = Project::where("Semester","=",$activeSemester->id)->get();
        //Create an easier to access array of projects
        $projectArray = array();
        foreach($projects as $project){
            $projectArray[$project->id] = $project;
        }

        //Let's now find the relation between project and track by pulling all the relationships for this semester
        $trackListing = PeoplesChoiceTracks::where("Semester","=",$activeSemester->id)->get();
        $trackListing = $trackListing->toArray();

        //Now we should loop through each track listing and create the appropriate track listing array to pass to the page
        $trackListingReturn = array();
        foreach($trackListing as $track){

            $tmpProj = $projectArray[$track["ProjectID"]];

            $data_arr = array("id"=>$track["ProjectID"], "UID"=>$tmpProj->UID, "Name"=>$tmpProj->Name,);

            if(array_key_exists($track["TrackNumber"], $trackListingReturn)){
                //Key exists, add to the array
                array_push($trackListingReturn[$track["TrackNumber"]]["classes"], $data_arr);
            }else{
                //Create the key
                $trackListingReturn[$track["TrackNumber"]] = array("trackNumber" => $track["TrackNumber"], "classes" => array($data_arr));
            }
        }
        //Build the response with all the projects
        return Response::json(array_merge($inputdata, array("tracks"=>$trackListingReturn)));
    }
>>>>>>> Stashed changes
}

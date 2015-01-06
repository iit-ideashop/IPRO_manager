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
}
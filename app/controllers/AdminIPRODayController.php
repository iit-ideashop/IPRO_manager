<?php
class AdminIPRODayController extends BaseController{
    
    function index(){
        //Show the initial iproday page with all of the ipro days as a drop down and their respective registrations.
        //Grab all the ipro days
        $iprodays = IPRODay::all();
        //Grab the most recent one
        $iproday_recent = IPRODay::all()->last();
        //next we have to pull the registration for the iproday
        $regRecords = Registration::leftJoin('registrants','registrant','=','registrants.id')->get();
        View::share('iprodays',$iprodays);
        View::share('iproday',$iproday_recent);
        View::share('reg_data',$regRecords);
        return View::make('admin.iproday.index');
        
        
        
    }
    
    
    
    
}

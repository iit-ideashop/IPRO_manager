<?php
class IPRODayRegistrationController extends BaseController {

    //$id refers to the ipro day id in the db
    public function showRegistration($id){
        //Pull the ipro from the database
        $iproday = IPRODay::find($id);
        if($iproday == NULL){
            //TODO: Figure out what to do if the ipro day does not exist
        }
        //TODO: add logical checks to see if registration is open
        
        //Next we need to pull the tracks and IPROS related
        $tracks = $iproday->Tracks()->get();
        $trackArray = array();//MultiDim array with Track as key and value as another array/collection of tracks
        foreach($tracks as $track){
            $iproarray = $track->iprotracks()->get();
            $trackArray[$track->id] = $iproarray;
        }
        View::share('iproday',$iproday);
        View::share('tracks',$tracks);
        View::share('tracksArray',$trackArray);
        return View::make('iproday.registration.register');
    }
    
}

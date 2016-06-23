<?php
class IPRODayRegistrationController extends BaseController {

    public function index(){
        //Let's pull all of the active ipro days from the db. 
        $today = date('Y-m-d H:i:s',time());
        $iprodays = IPRODay::where('registrationStart','<',$today)->where('registrationEnd','>',$today)->get();
        View::share('iprodays',$iprodays);
        return View::make('iproday.registration.index');
        
    }
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
    
    
    //Process the registration details from the showRegistration function
    public function register($id){
        //Ok, someone submitted our registration form, let's take their data and make a new registrant, or if the registrant already exists
        //via email address, then let's update the registration database and create a new registration
        $registrant = Registrant::where('email','=',Input::get('email'))->get();
        if($registrant->isEmpty()){
            //If the person has never before registered we need to make them a registrant entry
            $registrant = new Registrant;
            $registrant->firstName = Input::get('firstName');
            $registrant->lastName = Input::get('lastName');
            $registrant->organization = Input::get('organization');
            $registrant->phone = Input::get('phone');
            $registrant->address = Input::get('address');
            $registrant->email = Input::get('email');
            if(!$registrant->save()){
                return Redirect::to('/iproday/registration/'.$id)->with('error',array_merge(array('Please fill out all required fields'),$registrant->errors()->all()));
            }
        }else{
            $registrant = $registrant[0];
            //In this case we already have a submission, we need to get the object from the database, do a compare on the new data
            //and update if we don't have data or if new data has been entered. 
            if(Input::get('firstName') != ''){
                //Update the firstName
                $registrant->firstName = Input::get('firstName');
            }
            if(Input::get('lastName') != ''){
                $registrant->lastName = Input::get('lastName');
            }
            if(Input::get('organization') != ''){
                $registrant->organization = Input::get('organization');
            }
            if(Input::get('phone') != ''){
                $registrant->phone = Input::get('phone');
            }
            if(Input::get('address') != ''){
                $registrant->address = Input::get('address');
            }
            $registrant->save();
        }
        //We have now updated/created the registrant object accessible via $registrant, next let's pull the iproday object and make a new registration
        $iproday = IPRODay::find($id);
        //Next is the registration, we have to take the user's registration and either update it or make a new one, so.. let's check reg records
        $registration = Registration::where('iproday','=',$iproday->id)->where('registrant','=',$registrant->id)->get();
        //Let's check if this person already registered for IPRODay
        if($registration->isEmpty()){
            //Did not yet register, let's make a new registration
            $registration = new Registration;
            $registration->iproday = $iproday->id;
            $registration->registrant = $registrant->id;
            $registration->type = Input::get('attendeetype');
            if(Input::get('judgedBefore') == NULL){
                $registration->judgedBefore = false;
            }else{
                $registration->judgedBefore = Input::get('judgedBefore');
            }
            $registration->noPreferenceTrack = False;
            //Next we have to process the track preferences, processing just to be safe it is an array.
            $reg_trackarray = array();
            if(Input::get('trackSelection') != NULL){
            foreach(Input::get('trackSelection') as $trackSelection){
                if($trackSelection == 0){
                    $registration->noPreferenceTrack = True;
                }
                array_push($reg_trackarray, $trackSelection);
            }
            }
            $registration->trackPreferences = serialize($reg_trackarray);
            $registration->dietaryRestrictions = Input::get('dieraryRestrictions');
            if(!$registration->save()){
                return Redirect::to('/iproday/registration/'.$id)->with('error',array_merge(array('There was an error saving your registration'),$registration->errors()->all()));
            }
        }else{
            //User has already registered, let's update the user's registration
            $registration = $registration[0];
            //Lets update the type of registration
            if(Input::get('attendeetype') != ''){
                $registration->type = Input::get('attendeetype');
                
            }
            if(Input::get('judgedBefore') != ''){
                $registration->judgedBefore = Input::get('judgedBefore');
            }
            $registration->noPreferenceTrack = False;
            //Next we have to process the track preferences, processing just to be safe it is an array.
            $reg_trackarray = array();
            if(Input::get('trackSelection') != NULL){
            foreach(Input::get('trackSelection') as $trackSelection){
                if($trackSelection == 0){
                    $registration->noPreferenceTrack = True;
                }
                array_push($reg_trackarray, $trackSelection);
            }
            }
            $registration->trackPreferences = serialize($reg_trackarray);
            if(Input::get('dieraryRestrictions') != ''){
                $registration->dietaryRestrictions = Input::get('dieraryRestrictions');
            }
            if(!$registration->save()){
                return Redirect::to('/iproday/registration/'.$id)->with('error',array_merge(array('There was an error saving your registration'),$registration->errors()->all()));
            }
        }
        //user has been either created or edited, and registration has either been updated or created. Last part is the thank you email
        //Send the email
        Mail::send('emails.iproday.registration.thankyou', array('iproday'=>$iproday,'registrant'=>$registrant,'registration'=>$registration), function($message) use($registrant){
            $message->to($registrant->email)->subject('Thank you for registering for IPRO Day!');
        });
        //Show the confirmation page
        return Redirect::to('/iproday/registration')->with('success',array('Thank you for registering '.$registrant->firstName.'. We will send you a confirmation email shortly'));
    }
    
}

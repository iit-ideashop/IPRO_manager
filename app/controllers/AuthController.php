<?php
use Illuminate\Support\Facades\Config;

class AuthController extends BaseController {

    public function authenticate() {
        
        // get data from input
        $code = Input::get('code');

        // get google service
        $googleService = OAuth::consumer('Google');

        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {

            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

            
            if(in_array(@$result['hd'],Config::get('app.ApprovedHDs'))){
                //Email is part of the iit network

                $user_count = User::where('Email','=',$result['email'])->count();
                if($user_count >= 1){
                }else{
                    $user = new User;
                    $user->FirstName = $result['given_name'];
                    $user->LastName = $result['family_name'];
                    $user->Email = $result['email'];
                    $user->modifiedBy = "SYSTEM";
                    $user->save();
                }
                $loggedUser = User::where('Email','=',$result['email'])->lists('id');
                Auth::loginUsingId($loggedUser[0]);
                if((Session::has('routing.intended.route')) && ((Session::has('routing.intended.parameters')))){
                    //We have an intended route and parameters. Redirect to route and clear params with Session::pull
                    return Redirect::route(Session::pull('routing.intended.route'), Session::pull('routing.intended.parameters'));
                }else{
                    return Redirect::route('dashboard');
                }
            }else{
                echo 'Not authorized';
                return Redirect::route("unauthorized");
            }
        }
        // if not ask for permission first
        else {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri(array('prompt'=>'select_account'));

            // return to google login url
            return Redirect::to((string) $url);
        }
    }
    
    public function notAuthorized(){
        return View::make('notAuthorized');
    }
}

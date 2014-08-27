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

            $message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
            echo $message . "<br/>";

            if(in_array(@$result['hd'],Config::get('app.ApprovedHDs'))){
                //Email is part of the iit network
                echo 'Welcome to the iit network';
                $user_count = User::where('Email','=',$result['email'])->count();
                if($user_count >= 1){
                    echo 'You already exist in the DB';
                    
                }else{
                    echo 'Creating new user';
                    $user = new User;
                    $user->FirstName = $result['given_name'];
                    $user->LastName = $result['family_name'];
                    $user->Email = $result['email'];
                    $user->modifiedBy = "SYSTEM";
                    $user->save();
                }
                $loggedUser = User::where('Email','=',$result['email'])->lists('id');
                Auth::loginUsingId($loggedUser[0]);
                return Redirect::to('dashboard');
            }else{
                echo 'Not authorized';
                return Redirect::to("notAuthorized");
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
}

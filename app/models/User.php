<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('remember_token');
        
        public function Projects(){
            return $this->belongsToMany('Project','PeopleProjects','UserID','ClassID');
        }
        public function Orders(){
            return $this->hasMany('Order','PeopleID');
        }
        
        public function getFullName(){
            return $this->FirstName.' '.$this->LastName;
        }
        public function BudgetRequests(){
            return $this->hasMany('BudgetRequest','Requester');
            
        }
        static public function getFullNameWithId($id){
            $user = User::where('id','=',$id)->get();
            return $user[0]->FirstName.' '.$user[0]->LastName;
            
        }

        static public function checkRole($role, $userid = 0){
            if($userid == 0){
                $userid = Auth::id();
            }
            $result = DB::table('user_roles')->where('UserID','=', $userid)->where("Role",'=',$role)->get();
            if(count($result) > 0) {
                //User has the assigned role
                return true;
            }else{
                return false;
            }
        }
    //Create an image tag and display the profile picture
    static public function displayProfileImage($width = 100, $height = 100){
        $user = Auth::user();
        $image_src = "";
        $width = intval($width);
        $height = intval($height);
        if($user->profile_img == "use_google"){
            //We use the google image
            $image_src = $user->google_profile_img;
        }else{
            //We use the image in the assets folder
            $image_src = URL::asset(Config::get("app.StorageURLs.profile_images_url").$user->profile_img);
        }
        return '<img src="'.$image_src.'" width="'.$width.'" height="'.$height.'">';
    }
}

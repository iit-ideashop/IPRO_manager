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
        static public function getFullNameWithId($id){
            $user = User::find($id)->get();
            return $user[0]->FirstName.' '.$user[0]->LastName;
        }
}

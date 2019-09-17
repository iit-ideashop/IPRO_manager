<?php

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\BudgetRequest[] $BudgetRequests
 * @property-read \Illuminate\Database\Eloquent\Collection|\Order[] $Orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\Project[] $Projects
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string $FirstName
 * @property string $LastName
 * @property int|null $iGroupsID
 * @property string $Email
 * @property string|null $CWIDHash
 * @property int $isAdmin
 * @property string $modifiedBy
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $deleted_at
 * @property int $isPurchasing
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereCWIDHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereIGroupsID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\User whereUpdatedAt($value)
 */
class User extends Authenticatable{

    use Notifiable;
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
}

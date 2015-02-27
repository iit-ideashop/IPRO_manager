<?php
use LaravelBook\Ardent\Ardent;
class Project extends Ardent {
    public static $rules = array(
        'UID' => 'required|unique:projects,UID',
        'Name'=> 'required',
        'Description' => 'required',
        'Semester' => 'required',
    );
    public function Users(){
        return $this->belongsToMany('User','PeopleProjects','ClassID','UserID');
    }
    public function Account(){
        return $this->hasOne('Account','ClassID');      
    }
    public function Budgets(){
        return $this->hasManyThrough('Budget','Account','ClassID','AccountID');
    }
    public function BudgetRequests(){
        return $this->hasManyThrough('BudgetRequest','Account','ClassID','AccountID');
    }
    public function Semester(){
        return $this->belongsTo('Semester','Semester');
    }
    public function Orders(){
        return $this->hasMany('Order','ClassID');
    }
    public function Items(){
        return $this->hasManyThrough('Item','Order','ClassID','OrderID');
    }
    public function OrderNotes(){
        return $this->hasManyThrough('OrderNote','Order','ClassID','OrderID');
    }
    public function subProjects(){
        return $this->hasMany('Project','ParentClass');
    }

    public function parentProject(){
        return $this->belongsTo('Project','ParentID');
    }
    
    public static function getProjectUID($id){
        $project = Project::where('id','=',$id)->pluck('UID');
        return $project;
    }

    public function beforeSave(){
        //Before we save we have to make sure the modified by is updated
        $this->modifiedBy = Auth::id();
    }

    //This function checks that the logged in user is enrolled in the class he/she is trying to edit or do things to
    public function isEnrolled(){
        $enrollment = PeopleProject::where('UserID','=',Auth::id())->where('ClassID','=',$this->id)->get();
        if($enrollment->isEmpty()){
            return false;
        }else{
            return true;
        }
    }

    public function getAccessLevel(){
        //Returns the logged in users accessType for the project
        $accessType = PeopleProject::where('UserID','=',Auth::id())->where('ClassID','=',$this->id)->get();
        if($accessType->isEmpty()){
            return 0;
        }else{
            return $accessType[0]->AccessType;
        }
    }

}


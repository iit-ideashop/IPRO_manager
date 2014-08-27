<?php
use LaravelBook\Ardent\Ardent;
class Project extends Ardent {
    public static $rules = array(
        'UID' => 'required',
        'Name'=> 'required',
        'Description' => 'required',
        'Semester' => 'required',
        'modifiedBy' => 'required'
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
        return $this->hasMany('Project','id','ParentID');
    }
    public function parentProject(){
        return $this->belongsTo('Project','ParentID');
    }
    
    public static function getProjectUID($id){
        $project = Project::find($id)->pluck('UID');
        return $project;
    }
}


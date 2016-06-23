<?php
use LaravelBook\Ardent\Ardent;
class Semester extends Ardent {
    public static $rules = array(
        'Name' => array('required'),
        
        'ActiveStart' => array('required'),
        'ActiveEnd' => array('required'),
        'modifiedBy' => array('required')
    );
    
    public function Projects(){
        return $this->hasMany('Project','Semester');
    }
    public function modifiedBy(){
        return $this->belongsTo('User','id','modifiedBy');
    }
    
    public function makeActive(){
        //makes this semester the active semester
        DB::table('semesters')->update(array('Active'=>0));
        $this->Active = true;
        $this->save();
    }
    
    
    
}


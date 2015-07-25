<?php
use LaravelBook\Ardent\Ardent;
class ProjectScrum extends Ardent {
    //There are currently no relations for the project scrum

    protected $table = "projectScrums";
    public function beforeCreate(){
        //Before we save we have to make sure the modified by is updated
        $this->CreatedBy = Auth::id();
    }
}
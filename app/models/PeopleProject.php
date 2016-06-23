<?php
use LaravelBook\Ardent\Ardent;
class PeopleProject extends Ardent {
    protected $table = "PeopleProjects";

    public function beforeSave(){
        //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }
}


<?php
//This controller controls all api output for the projects api, all responses should be in json format.
class ProjectAPIController extends BaseController{

    public function getGroups($projectid){
        //Pull down the project
        $project = Project::where('id','=',$projectid)->first();
        //Find the group listing
        $groups = $project->subProjects()->get();
        //now we need to build our own array
        $groupArray = array();
        foreach($groups as $group){
            //Take the group and make its own key in the Grouparray
            $groupArray[$group->UID] = array();

        }

    }

    public function addGroup(){

    }

    public function removeGroup(){

    }

    public function enrollStudent(){

    }

    public function dropStudent(){

    }

    public function transferFunds(){

    }


}


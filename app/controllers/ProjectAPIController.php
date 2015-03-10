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
            //Next make Array 0 the group data as an array and array 1 as the user array
            $groupArray[$group->UID][0] = array($group->id,$group->UID, $group->Name);
            //Pull down the group's enrolled students
            $groupArray[$group->UID][1] = array();
            $students = $group->Users()->get();
            foreach($students as $student){
                array_push($groupArray[$group->UID][1], array($student->id,$student->FirstName, $student->LastName,$student->Email));
            }
        }
        return Response::json($groupArray,$status=200);

    }

    public function getStudents($projectid){
        //Pull down the project
        $project = Project::where('id','=',$projectid)->first();
        //Pull the users
        $students = $project->Users()->get();
        //Package it up in a different array
        $studentArray = array();
        foreach($students as $student){
            array_push($studentArray, array($student->id,$student->FirstName,$student->LastName,$student->Email));
        }
        return Response::json($studentArray,$status=200);

    }

    public function addGroup($projectid){
        //Test the jquery ajax code
        $groupUID = Input::get("groupUID");
        $groupName = Input::get("groupName");
        $groupDesc = Input::get("groupDesc");
        //Now let's take the data and make a new group
        $project = new Project;

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


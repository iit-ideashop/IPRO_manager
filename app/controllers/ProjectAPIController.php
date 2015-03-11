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
            $studentobj = array();
            $studentobj['value'] = $student->FirstName.' '.$student->LastName;
            $studentobj['data'] = $student->Email;
            //array_push($studentArray, array($student->id,$student->FirstName,$student->LastName,$student->Email));
            array_push($studentArray, $studentobj);
        }
        return Response::json($studentArray,$status=200);

    }

    public function addGroup($projectid){
        $parentProject = Project::where('id','=',$projectid)->first();
        //Test the jquery ajax code
        $groupUID = Input::get("groupUID");
        $groupName = Input::get("groupName");
        $groupDesc = Input::get("groupDesc");
        //return JSON object
        $returnObj = array();
        $returnObj['error'] = false;
        $returnObj['errorarr'] = array();
        //Check for valid data
        if($groupUID == ""){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'],"You must provide a unique group identifier");
        }
        if($groupName == ""){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'],"You must provide a group name");
        }
        if(!$returnObj['error']){
            //Now let's take the data and make a new group
            $project = new Project;
            $project->UID = $parentProject->UID.'-'.$groupUID;
            $project->Name = $groupName;
            if($groupDesc == ''){
                $project->Description = $parentProject->UID.'-'.$groupUID." ".$groupName;
            }else{
                $project->Description = $groupDesc;
            }
            $project->Semester = $parentProject->Semester;
            $project->ParentClass = $parentProject->id;
            $project->CRN = 0;
            $project->iGroupsID = 0;
            $project->save();
            //Once the project is saved we need to setup a spending account for the IPRO
            $account = new Account;
            $account->ClassID = $project->id;
            $account->save();

            return Response::json($returnObj);
        }else{
            return Response::json($returnObj);
        }
    }

    public function removeGroup(){

    }

    public function enrollStudent($projectid){
        $parentProject = Project::where('id','=',$projectid)->first();
        $groupid = Input::get('groupid');
        $studentEmail = Input::get('studentEmail');
        $subgroup = Project::where('id','=',$groupid)->first();
        $student = User::where('Email','=',$studentEmail)->first();
        //return JSON object
        $returnObj = array();
        $returnObj['error'] = false;
        $returnObj['errorarr'] = array();
        //Check if group exists
        if($subgroup == null){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Group does not exist");
        }
        if($student == null){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Student does not exist");
        }
        //we checked for fatal errors, if there are errors at this point we should just return the JSON
        if($returnObj['error']){
            return Response::json($returnObj);
        }
        //Next we verify the subgroup is a subgroup of the parent group
        if($subgroup->ParentClass != $parentProject->id){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Group is not associated with parent");
        }
        //Check enrollment
        if(!$parentProject->isStudent($student->id)){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Student not enrolled in parent project");
        }
        //Check to see if already enrolled
        if($subgroup->isStudent($student->id)){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Student already in subgroup");
        }
        //Verifications completed. Let's enroll the student in this subgroup
        if(!$returnObj['error']) {
            $enrollment = new PeopleProject;
            $enrollment->UserID = $student->id;
            $enrollment->ClassID = $subgroup->id;
            $enrollment->AccessType = 1;
            $enrollment->save();
            $returnObj['success'] = true;
         }
        return Response::json($returnObj);
    }

    public function dropStudent($projectid){
        //Get the posted data and pull the project
        $parentProject = Project::where('id','=',$projectid)->first();
        $groupid = Input::get("groupid");
        $studentEmail = Input::get("studentEmail");
        //Lets pull the subgroup
        $subgroup = Project::where('id','=',$groupid)->first();
        $student = User::where('Email','=',$studentEmail)->first();
        //return JSON object
        $returnObj = array();
        $returnObj['error'] = false;
        $returnObj['errorarr'] = array();
        //Check if group exists
        if($subgroup == null){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Group does not exist");
        }
        if($student == null){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Student does not exist");
        }
        //we checked for fatal errors, if there are errors at this point we should just return the JSON
        if($returnObj['error']){
            return Response::json($returnObj);
        }
        //Next we verify the subgroup is a subgroup of the parent group
        if($subgroup->ParentClass != $parentProject->id){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Group is not associated with parent");
        }
        //Check to see if already enrolled
        if(!$subgroup->isStudent($student->id)){
            $returnObj['error'] = true;
            array_push($returnObj['errorarr'], "Something went wrong. ERR: Student not enrolled in subgroup");
        }
        //Verifications completed. Let's drop the student from this subgroup
        if(!$returnObj['error']) {
            $enrollment = PeopleProject::where("UserID","=",$student->id)->where("ClassID","=",$subgroup->id)->first();
            if($enrollment == null){
                $returnObj['error'] = true;
                array_push($returnObj['errorarr'], "Something went wrong. ERR: Student not enrolled in subgroup");
            }else{
                $enrollment->delete();
                $returnObj['success'] = true;
            }
        }
        return Response::json($returnObj);

    }

    public function transferFunds(){

    }


}


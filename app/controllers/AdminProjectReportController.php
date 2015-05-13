<?php

class AdminProjectReportController extends BaseController{

    public function showReports($sem_id){
        //semid is the semester we are looking at. We should show reports that can be generated for a project
        View::share("semesterId",$sem_id);
        return View::make('admin.projects.reportListing');

    }

    public function budgetReport($sem_id){
        //We need to build a budget report for the entire semester here. So pull all projects from this semester and mold into the data we need
        $projects = Project::where("Semester","=",$sem_id)->get();
        //Loop through the projects and calculate
        // - Enrolled student count
        // - Teambuilding money(only lvl 1 projects)
        // - money allocated
        // - money spent

        foreach($projects as $project){
            //find project enrollment
            $project->enrollment = PeopleProject::where("ClassID","=",$project->id)->where("AccessType","=",1)->count();
            //Accounting data
            $accountid = Account::where("ClassID","=",$project->id)->lists("id");
            $accountid = $accountid[0];
            //Pull budgets allocated for money allocated data
            $budgets = Budget::where("AccountID","=",$accountid)->get();
            //Calculate money allocated
            $project->moneyAllocated = 0;
            foreach($budgets as $budget){
                $project->moneyAllocated += $budget->Amount;
            }
            //Calculate money spent
            $ledgerEntries = ledgerEntry::where("AccountNumber",'=',$accountid)->where("Debit",">",0.00)->get();
            $project->moneySpent = 0;
            foreach($ledgerEntries as $ledgerEntry){
                $project->moneySpent += $ledgerEntry->Debit;
            }
            //Teambuilding if a level 1 project
            if($project->ParentClass == NULL){
                $project->teambuilding = 10 * $project->enrollment;
            }else{
                $project->teambuilding = 0;
            }
        }

        //Calculate tier 1 spent and allocated
        foreach($projects as $project){
            $subClasses = array();
            if($project->ParentClass == NULL){
                //Find level 2 children and add them to the list
                foreach($projects as $class){
                    if($class->ParentClass == $project->id){
                        array_push($subClasses, $class->id);
                    }
                }
                //Recursively find all the subclasses
                $recurse = true;
                while($recurse){
                    $recurse = false; // Turn off recursion, the only way we continue is if we find a child relationship
                    foreach($projects as $class){
                        if(in_array($class->ParentClass,$subClasses)){
                            array_push($subClasses, $class->id);
                        }
                    }
                }
            }
            //Now we know all of the subclasses for this project, lets recalculate it's monies
            foreach($projects as $class){
                if(in_array($class->id, $subClasses)){
                    //Hit, this is a sub class
                    //Add data from the sub class into the parent class
                    $project->moneySpent += $class->moneySpent;
                    $project->moneyAllocated += $class->moneyAllocated;
                }
            }
        }
        //We now have enough data to build a page with information
        View::share("projects",$projects);
        return View::make("admin.projects.reports.budget");
    }
    
}
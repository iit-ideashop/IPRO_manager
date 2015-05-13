<?php

class AdminProjectReportController extends BaseController{

    public function showReports($sem_id){
        //semid is the semester we are looking at. We should show reports that can be generated for a project
        View::share("semesterId",$sem_id);
        return View::make('admin.projects.reportListing');

    }

    public function budgetReport($sem_id){
        //We need to build a budget report for the entire semester here. So pull all projects from this semester and mold into the data we need
        //Check if we are exporting an excel report
        $reportType = Input::get("type");
        $active_semester = Semester::where("id","=",$sem_id)->first();
        if($reportType != "excel"){
            $reportType = "default";
        }
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
        //Check if we are outputting excel report
        if($reportType == "excel"){
            //Create a new spreadsheet in memory
            $objPHPExcel = new PHPExcel();
            //Set properties
            $objPHPExcel->getProperties()->setCreator("IPRO Manager");
            $objPHPExcel->getProperties()->setLastModifiedBy("IPRO Manager");
            $objPHPExcel->getProperties()->setTitle("IPRO Manager Budget Report");
            $objPHPExcel->getProperties()->setSubject("IPRO Manager Budget Report");
            $objPHPExcel->getProperties()->setDescription("Report generated by IPRO Manager");
            //Generate the Guest page first
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Budget as of '.date('m/d/Y g:i a',time()).' for '.$active_semester->Name);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
            $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Unique ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B2', '');
            $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Students Enrolled');
            $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Teambuilding Budget');
            $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Money Allocated');
            $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Money Spent');
            $objPHPExcel->getActiveSheet()->setTitle('Budget Report');
            $cell_counter = 3;
            //Here we start to loop through all the data
            foreach($projects as $parentClass){
                if($parentClass->ParentClass == NULL){
                    //Print data
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cell_counter, $parentClass->UID);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$cell_counter.':B'.$cell_counter);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cell_counter, $parentClass->Name);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cell_counter, $parentClass->enrollment);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cell_counter, $parentClass->teambuilding);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cell_counter, $parentClass->moneyAllocated);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cell_counter, $parentClass->moneySpent);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                    $cell_counter++;
                    //Now we look for child classes
                    foreach($projects as $childClass){
                        if($childClass->ParentClass == $parentClass->id){
                            //Child of parent
                            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cell_counter, '-');
                            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cell_counter, $childClass->UID);
                            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cell_counter, $childClass->Name);
                            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cell_counter, $childClass->enrollment);
                            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cell_counter, "-");
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$cell_counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cell_counter, $childClass->moneyAllocated);
                            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cell_counter, $childClass->moneySpent);
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$cell_counter)->getNumberFormat()->setFormatCode('$#,##0.00');
                            $cell_counter++;
                        }
                    }
                }
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $excel_filename = 'IPRO_BUDGET_REPORT_'.date('_m-d-Y',time()).'.xlsx';
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Type: application/octet-stream');
            //header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="'.$excel_filename.'"');
            $objWriter->save("php://output");
        }else{
            //Build the default page
            View::share("projects",$projects);
            return View::make("admin.projects.reports.budget");
        }




    }
    
}
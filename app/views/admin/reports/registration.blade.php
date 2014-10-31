<?php
        //Grab the data from the database
        $iproDay = IPRODay::find($IPROID);
        if($iproDay == NULL){
            echo 'IPRO DAY with specific ID does not exist.';
            exit;
        }
        //We have an iproday, get the registration and track records
        $regRecords = Registration::leftJoin('registrants','registrant','=','registrants.id')->where('iproday','=',$iproDay->id)->get();
        $tracks = Track::where('iproday','=',$iproDay->id)->get();
        //We need to convert the tracks collection into a simple array kvp
        $trackNamearr = array();
        foreach($tracks as $track){
            $trackNamearr[$track->id] = $track;
        }
        
        //Generate the Guest page first
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Guest Registration as of '.date('m/d/Y g:i a',time()).' for IPRO Day on '.date('m/d/Y',strtotime($iproDay->eventDate)));
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'First Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Last Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Organization');
        $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Registration Time');
        $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Registration Updated');
        $objPHPExcel->getActiveSheet()->setTitle('Guest Registration');
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Judge Registration as of '.date('m/d/Y g:i a',time()).' for IPRO Day on '.date('m/d/Y',strtotime($iproDay->eventDate)));
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'First Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Last Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Organization');
        $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Registration Time');
        $objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Registration Updated');
        $objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Dietary Restrictions');
        $judge_starting_letter = 'H';
        $idColumnMapping = array();
        //Loop the tracks
        foreach($tracks as $track){
            $objPHPExcel->getActiveSheet()->SetCellValue($judge_starting_letter.'2', $track->name);
            $idColumnMapping[$track->id] = $judge_starting_letter;
            $judge_starting_letter++;
        }
        $objPHPExcel->getActiveSheet()->SetCellValue($judge_starting_letter.'2', 'No Preference');
        $idColumnMapping[0] = $judge_starting_letter;
        $judge_starting_letter++;
        $objPHPExcel->getActiveSheet()->setTitle('Judge Registration');
        $guest_counter = 3;//Start on row 3
        $judge_counter = 3;//start on row 3
        foreach($regRecords as $regRecord){
            if($regRecord->type == "Guest"){
                //Write Guest Record
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$guest_counter, $regRecord->firstName);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$guest_counter, $regRecord->lastName);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$guest_counter, $regRecord->organization);
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$guest_counter, $regRecord->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$guest_counter, date('m/d/Y g:i a',strtotime($regRecord->created_at)));
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$guest_counter, date('m/d/Y g:i a',strtotime($regRecord->updated_at)));
                $guest_counter++;
            }elseif($regRecord->type == "Judge"){
                //Write Judge Record
                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$judge_counter, $regRecord->firstName);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$judge_counter, $regRecord->lastName);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$judge_counter, $regRecord->organization);
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$judge_counter, $regRecord->email);
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$judge_counter, date('m/d/Y g:i a',strtotime($regRecord->created_at)));
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$judge_counter, date('m/d/Y g:i a',strtotime($regRecord->updated_at)));
                //Loop the judges tracks
                foreach(unserialize($regRecord->trackPreferences) as $trackPref){
                    $objPHPExcel->getActiveSheet()->SetCellValue($idColumnMapping[$trackPref].$judge_counter, 'x');
                }
                $judge_counter++;
            }
        }
        $excel_filename = 'IPRODay Registration Report '.date('m-d-Y',time()).'.xlsx';
        
?>
@include('admin.reports.generator')
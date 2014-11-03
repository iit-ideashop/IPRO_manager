<?php 
/*
 * The primary purpose of this file is to generate the report file and allow the user to download it to their browser
 * this file has the functions required to name the file, and to set the headers for download
 * 
 * inputs: $excel_filename is the filename the file will take on
 */
    //Save Excel 2007 file
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //header('Content-Type: application/octet-stream');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$excel_filename);
    $objWriter->save("php://output");
?>

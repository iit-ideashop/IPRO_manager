<?php
//Controller takes care of all controller functions for the printing route
class PrintingController extends BaseController{

    //Based on the type of credentials the user has we will show a different set of processes and stuff to the user
    public function index(){
        //Route for admins = printing.awaitingApproval
        //Route for print role = printing.awaitingPrint
        //check for admin
        if(Auth::user()->isAdmin){
            //User is an admin
            return Redirect::route("printing.awaitingApproval");
        }else if(User::checkRole("ROLE_PRINTING")){
            //User is a printer, redirect to the printers route
            return Redirect::route("printing.awaitingPrint");
        }else{
            //User has no access to this page.
            return Redirect::route("dashboard");
        }
    }

    public function awaitingApproval(){
        //Pull down the files awaiting approval
        $files = PrintSubmission::where("status","=",2)->get();
        View::share("files",$files);
        return View::make('printing.awaitingApproval');
    }

    public function awaitingPrint(){
        //Pull down files awaiting print by the printer
        $files = PrintSubmission::where("status","=",3)->get();
        View::share("files",$files);
        return View::make('printing.awaitingPrint');
    }

    public function printed(){
        //Pull down files printed by the printer
        $files = PrintSubmission::where("status","=",4)->get();
        View::share("files",$files);
        return View::make('printing.printed');
    }

    public function checkInPosters(){
         $files = PrintSubmission::where("status","=",4)->get();
         View::share("files",$files);
         return View::make('printing.checkinposter');
    }

    public function posterPickup(){
        //Pull down files printed by the printer
        $files = PrintSubmission::where("status","=",5)->get();
        View::share("files",$files);
        return View::make('printing.posterPickup');
    }

    public function projectReport(){

    }

    public function downloadFile($fileid){
        //Check for auth of admin or printadmin or see if user is enrolled in the project to be allowed to download the file
        return Response::download(Config::get("app.StorageURLs.printSubmissions")."2_Poster.pdf","My_Little_Pony.pdf");
    }

    public function viewFile($fileid){
        $file = Config::get("app.StorageURLs.printSubmissions")."2_Poster.pdf";  // <- Replace with the path to your .pdf file
        // check if the file exists
        echo $file;
        if (file_exists($file)) {
            // read the file into a string
            $content = file_get_contents($file);
            // create a Laravel Response using the content string, an http response code of 200(OK),
            //  and an array of html headers including the pdf content type
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
        }else{
            return Response::make("File does not exist or is not available");
        }
    }



    //***** The following functions are API functions used for JSON requests ******//



}


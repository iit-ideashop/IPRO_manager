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

    }

    public function printed(){

    }

    public function awaitingPickup(){

    }

    public function projectReport(){

    }

    public function downloadFile($fileid){
        //Check for auth of admin or printadmin or see if user is enrolled in the project to be allowed to download the file
        return Response::download(Config::get("app.StorageURLs.printSubmissions")."2_Poster.pdf","My_Little_Pony.pdf");
    }
}


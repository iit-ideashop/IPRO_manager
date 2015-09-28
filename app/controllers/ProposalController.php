<?php

class ProposalController extends BaseController{
    
    public function index(){
        if(Auth::check()){
            //User is authenticated with an account in our systems
            return Redirect::route("dashboard")->with("error",array("Proposal Management is not yet available"));
        }else{
            //User is not authenticated with an account in our system
            return View::make("proposals.index");
        }
    }
}


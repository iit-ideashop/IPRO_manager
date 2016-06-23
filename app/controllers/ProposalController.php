<?php

class ProposalController extends BaseController{
    
    public function index(){
        if(Auth::check()){
            //User is authenticated with an account in our systems
            echo "Hello secured proposals";
        }else{
            //User is not authenticated with an account in our system

            return View::make("proposals.index");
        }
    }
}


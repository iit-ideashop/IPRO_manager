<?php

class AdminController extends BaseController{
    
    public function dashboard(){
        phpinfo();
        exit;
        return Response::make('hi');
        
        
    }
    
}
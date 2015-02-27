<?php

class ProjectController extends BaseController{
    
    public function Index($id){
        $data = array();

        $class = Project::find($id);
        //Check for authorization
        //Make sure that the user is enrolled in the project
        if(!$class->isEnrolled()){
            return Redirect::route('dashboard')->with('error',array('You must be enrolled in the class to see that page'));
        }
        //Let's get this projects account
        $account = Account::where('ClassID','=',$class->id)->get();
        if($account->isEmpty()){
            $account = new Account;
            $account->Balance = 0.00;
            Session::flash('error',array('Your project is missing a spending account. Please contact the administrator to setup a spending account'));
        }else{
            //Once we have the class we need to pull the budgets for the class
            Session::forget('error');//Removes a bug where the flash is kept for another session.
            $account = $account[0];//Grab only 1 account
        }
        $orders = Order::where('ClassID','=',$class->id)->get();
        $budgets = Budget::where('AccountID','=',$account->id)->get();
        //Next it's budget requests
        $budget_requests = BudgetRequest::where('AccountID','=',$account->id)->get();
        
        //Pack the data
        $data['class'] = $class;
        $data['account'] = $account;
        $data['orders'] = $orders;
        $data['budgets'] = $budgets;
        $data['budgetRequests'] = $budget_requests;
        return View::make('project.index',$data);
    }


    public function showOrders($id){

    }

    //Show the faculty group manager to create smaller groups for the class
    public function groupManager($projectid){
        $class = Project::find($projectid);
        View::share('class',$class);
        $account = $class->Account()->get();
        if($account->isEmpty()){
            $account = new Account;
            $account->Balance = 0.00;
            Session::flash('error',array('Your project is missing a spending account. Please contact the administrator to setup a spending account'));
        }else{
            //Once we have the class we need to pull the budgets for the class
            Session::forget('error');//Removes a bug where the flash is kept for another session.
            $account = $account[0];//Grab only 1 account
        }
        View::share('account',$account);
        //Next we need to pull the subprojects and pass them to the page so they can be listed
        $groups = $class->subProjects()->get();
        View::share('groups',$groups);

        return View::make('project.groupManager');
    }
}


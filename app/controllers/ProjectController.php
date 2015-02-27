<?php

class ProjectController extends BaseController{
    
    public function Index($id){
        $data = array();

        $class = Project::find($id);
        //Let's get this projects account
        $account = Account::where('ClassID','=',$class->id)->get();
        if($account->isEmpty()){
            $account = new Account;
            $account->Balance = 0.00;
            Session::flash('error',array('Your project is missing a spending account. Please contact the administrator to setup a spending account'));
        }else{
            //Once we have the class we need to pull the budgets for the class
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

    //Show a page containing ipro orders
    public function showOrders($id){

    }

    //Show a page with the class roster
    public function showRoster($id){

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
            $account = $account[0];//Grab only 1 account
        }
        View::share('account',$account);
        //The group manager page contains all the code required to show groups and make modifications via a simple api and ajax calls. Let's load the page and let
        //javascript handle things from this point on
        return View::make('project.groupManager');
    }
}


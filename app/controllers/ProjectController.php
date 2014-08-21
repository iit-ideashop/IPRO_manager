<?php

class ProjectController extends BaseController{
    
    public function Index($id){
        $data = array();
        $data['selected_class'] = $id;
        //TODO: implement authorixation to allow only authorized users to view this page
        $class = Project::find($id);
        //Let's get this projects account
        $account = Account::where('ClassID','=',$class->id)->get();
        //Once we have the class we need to pull the budgets for the class
        $account = $account[0];//Grab only 1 account
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
}


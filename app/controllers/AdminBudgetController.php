<?php

class AdminBudgetController extends BaseController{
    
    //shows the main page on the buget requests
    public function index(){
        //We need to pull in the budget requests in descending order with status 1
        $budgets = BudgetRequest::active()->orderBy('created_at','desc')->get();
        View::share('budgetRequests',$budgets);
        $budgetsApproved =  BudgetRequest::approved()->orderBy('updated_at')->limit(5)->get();
        View::share('approvedBudgets',$budgetsApproved);
        return View::make('admin.budgets.index');
    }
    
    public function viewRequest($id){
        //Passsed id is the request id we are trying to view
        //If the request exists we show the request, if not we have to redirect with errors
        $budgetRequest = BudgetRequest::find($id);
        if($budgetRequest == null){
            return Redirect::route('admin_budgets')->with('error',array(''));
        }else{
            
        }

    }
    
    public function denyBudget(){
        
    }
    
    public function approveBudget(){
        //Add an element that checks for how many $$$ have been approved
        
    }
}


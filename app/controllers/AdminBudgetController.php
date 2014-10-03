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
    
    
}


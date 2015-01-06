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
    
    public function viewBudget(){
        
        
    }
    
    //Show a budget request
    public function viewRequest($id){
        //Passsed id is the request id we are trying to view
        //If the request exists we show the request, if not we have to redirect with errors
        $budgetRequest = BudgetRequest::find($id);
        if($budgetRequest == null){
            return Redirect::route('admin_budgets')->with('error',array(''));
        }else{
            
        }

    }
    
    public function deny(){
        //Denying a budget is easy
        //Pull the request, set it to denied, put down who denied it and why, send a sorry email
        //Post Data
        $comment = Input::get('requestComment');
        $budgetID = Input::get('requestID');
        //Pull the request
        $request = BudgetRequest::find($budgetID);
        //Take this request and mark it as denied + update the comment
        $request->Status = 3;//Rejected
        $request->Comment = $comment;
        $request->save();
        //Budget has been denied, now to send an email to the person who requested the budget
        $requester = $request->Requester()->get();
        $requester = $requester[0];        
        //Mail prototype
        Mail::send('emails.budgets.budgetDenied', array('person'=>$requester,'budgetRequest'=>$request), function($message) use($requester){
                    $message->to($requester->Email,$requester->FirstName.' '.$requester->LastName)->subject('IPRO budget denied');
        });
        return Redirect::route('admin_budgets')->with('success',array('Budget was successfully denied'));
    }
    
    public function approve(){
        //Function used to approve budgets
        //must provide request id, comments and budget approval
        //Post data
        $comment = Input::get('requestComment');
        $amount = floatval(Input::get('budgetAmount'));
        $budgetID = Input::get('requestID');
        //Pull the budget request
        $request = BudgetRequest::find($budgetID);
        //Some quick error checking, cannot approve more than requested
        if($request->Amount < $amount){
            return Redirect::route('admin_budgets')->with('error',array('You cannot approve more money than requested for a budget'));
        }
        //Approve the request
        $request->Status = 2;
        $request->Comment = $comment;
        $request->save();
        //New budget info
        $budget = new Budget;
        $budget->AccountID = $request->AccountID;
        $budget->Amount = $amount;
        $budget->Terms = $request->Request;
        $budget->Comment = $comment;
        $budget->Requester = $request->Requester;
        $budget->Approver = Auth::id();
        $budget->save();
        $account = $budget->Account()->get();
        $account[0]->Deposit('BUDGET',$amount,$budget->id);
        $requester = $request->Requester()->get();
        $requester = $requester[0];
        Mail::send('emails.budgets.budgetApproved', array('person'=>$requester,'budgetRequest'=>$request,'budget'=>$budget), function($message) use($requester){
                    $message->to($requester->Email,$requester->FirstName.' '.$requester->LastName)->subject('IPRO budget approved');
        });
        return Redirect::route('admin_budgets')->with('success',array('Budget was successfully Approved'));
    }
}


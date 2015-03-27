<?php

class ProjectController extends BaseController{
    
    public function Index($id){
        $class = Project::find($id);
        //Let's get this projects account
        $account = $class->Account()->first();
        $orders = Order::where('ClassID','=',$class->id)->get();
        $budgets = Budget::where('AccountID','=',$account->id)->get();
        //Next it's budget requests
        $budget_requests = BudgetRequest::where('AccountID','=',$account->id)->get();
        
        //Pack the data
        View::share("orders",$orders);
        View::share("budgets",$budgets);
        View::share("budgetRequests",$budget_requests);
        return View::make('project.index');
    }

    //Show a page containing ipro orders
    public function showOrders($id){
        $class = Project::find($id);
        $orders = Order::where('ClassID','=',$class->id)->get();
        View::share('orders',$orders);
        return View::make('project.orders');

    }

    //Show a page with the class roster
    public function showRoster($id){
        $class = Project::find($id);
        $students = $class->Users()->get();
        View::share('students',$students);
        return View::make('project.roster');
    }

    //Show the faculty group manager to create smaller groups for the class
    public function groupManager($projectid){
        //The group manager page contains all the code required to show groups and make modifications via a simple api and ajax calls. Let's load the page and let
        //javascript handle things from this point on
        return View::make('project.groupManager');
    }

    //Show the print submission page for users
    public function printSubmission($projectid){

        return View::make("project.printSubmission");
    }

    public function printSubmissionUpload($projectid){
        sleep(3);
        return Response::json("hey");
    }
}


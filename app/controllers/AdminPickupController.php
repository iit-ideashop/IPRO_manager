<?php

class AdminPickupController extends BaseController{


    function index(){
        //Show the index page for a pickup request
        return View::make("admin.pickup.index");
    }

    function search(){
        //This function will take the submitted ID#, find the user and redirect to the view orders page
        $studentID = Input::get('idnumber');
        $studentFName = Input::get('firstName');
        $studentLName = Input::get('lastName');
        $studentEmail = Input::get('email');
        //Now let's find the user in the database who's ID number that is
        $student = User::where('CWIDHash','LIKE',md5($studentID))->orWhere('FirstName','LIKE',$studentFName)->orWhere('LastName','LIKE',$studentLName)->orWhere('Email','LIKE',$studentEmail)->get();
        if($student->isEmpty()){
            return Redirect::route('admin.order.pickup')->with('error',array('Could not find a user that matches your query'));
        }
        //Ok so we have atleast one user in our collection. If there is 1 user we can move onto the view pickup page
        //If the query gave us mutliple users we have to find out which user we want to perform a pickup for
        if($student->count() == 1){
            //Redirect to a different controller with the user info
            return Redirect::action('AdminPickupController@viewItems', array('userid' => $student[0]->id));
        }else{
            View::share('students',$student);
            return View::make('admin.pickup.searchResults');
        }
    }
    //Show a listing of items the user is allowed to pickup
    function viewItems(){
        $userid = Input::get('userid');
        //Pull the user from the databse
        $student = User::find(intval($userid));
        if($student == null){
            return Redirect::route('admin.order.pickup')->with('error',array('The specified user does not exist, please try again'));
        }
        View::share('student',$student);
        //Ok we have a user object, now to find the user's orders
        //Start by pulling the ID's of the orders the user owns
        $ownedorders = Order::where('PeopleID','=',$student->id)->lists('id');
        //Next we have to pull the orders which the user is a 3rd party pickup for
        $allowedPickup = ApprovedPickup::where('PersonID','=',$student->id)->lists('OrderID');
        $allpickupIDs = array_unique(array_merge($ownedorders,$allowedPickup), SORT_REGULAR);
        //Pull only orders that are in status 3 "Ready for pickup", get their id's so we can find items
        $orderIDs = Order::whereIn('id',$allpickupIDs)->where('Status','=','3')->lists('id');
        //Pull items avaliable for pickup
        $items = Item::whereIn('OrderID',$orderIDs)->where('Status','=','4')->get();
        View::share('items',$items);
        return View::make('admin.pickup.viewItems');
    }

}


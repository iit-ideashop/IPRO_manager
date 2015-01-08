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
        $items = Item::whereIn('OrderID',$orderIDs)->where('Status','=','4')->where('barcode','!=','null')->get();
        View::share('items',$items);
        return View::make('admin.pickup.viewItems');
    }

    function createPickup()
    {
        //This function will receive a JSON array of barcodes
        $studentid = Input::get('pickupuserid');
        $itemIDs = json_decode(Input::get('ItemIDs'));
        if(sizeof($itemIDs) < 1){
            //We have 0 items in the array
            return Redirect::route('admin.order.pickup.viewItems', array('userid' => $studentid))->with('error', array('No items were marked for pickup. Try again'));
        }
        $student = User::find($studentid);
        //take the items we just received and pull the items the user can get from the database and verify the intersections.
        $ownedorders = Order::where('PeopleID', '=', $student->id)->lists('id');
        //Next we have to pull the orders which the user is a 3rd party pickup for
        $allowedPickup = ApprovedPickup::where('PersonID', '=', $student->id)->lists('OrderID');
        $allpickupIDs = array_unique(array_merge($ownedorders, $allowedPickup), SORT_REGULAR);
        //Pull only orders that are in status 3 "Ready for pickup", get their id's so we can find items
        $orderIDs = Order::whereIn('id', $allpickupIDs)->where('Status', '=', '3')->lists('id');
        //Pull items avaliable for pickup
        $items = Item::whereIn('OrderID', $orderIDs)->where('Status', '=', '4')->where('barcode', '!=', 'null')->lists('id');
        foreach ($itemIDs as $itemid) {
            //Check for an intersection
            if (!in_array($itemid, $items)) {
                return Redirect::route('admin.order.pickup.viewItems', array('userid' => $studentid))->with('error', array('Some submitted items could not be picked up by this user. Try again'));
            }
            //Also check that each item attempting to be picked up does not already have a pickupItem record
            $pickupitem = PickupItem::where("ItemID","=",$itemid)->get();
            if(!$pickupitem->isEmpty()){
                return Redirect::route('admin.order.pickup.viewItems', array('userid' => $studentid))->with('error', array('A selected item is already part of another active pickup.'));
            }
        }
        //Verified that all items can be picked up, lets generate a pickup and give the 4 digit code so we can get on with this show.
        $pickup = new Pickup();
        //Lets populate this pickup.
        $pickup->PersonID = $student->id;
        //Generate a 4 digit code omitting 0-999 since 0's are demons
        $code_valid = false;
        $code = "";
        $digits = 4;
        while (!$code_valid) {
            $code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            //Make sure our pickup request has a unique retreive code
            $checkCode = Pickup::where("RetreiveCode","=",$code)->get();
            if($checkCode->isEmpty()){
                $code_valid = true;
            }
        }
        $pickup->RetreiveCode = $code;
        $pickup->Completed = false;
        $pickup->save();
        //Pickup now has an ID, lets generate some pickup items.
        foreach($itemIDs as $itemids){
            $pickupitem = new PickupItem();
            $pickupitem->ItemID = $itemids;
            $pickupitem->PickupID = $pickup->id;
            $pickupitem->save();
        }
        //Okay great. all has been generated, we should be good to go to create a pickup. let's show the page with the code and override options.
        return Redirect::route("admin.order.pickup.showCode",array("id"=>$pickup->id));
    }

    public function viewPickup($pickupid){
        //In this function we need to pull the pickup id and generate the pickup page.
        //Grab the pickup Id from the database
        $pickup = Pickup::find($pickupid);
        if($pickup == null){
            return Redirect::route('admin.order.pickup')->with('error', array('The Specified pickup could not be found'));
        }
        //Lets take the pickup and find the pickup's items.
        $pickupItems = $pickup->PickupItems()->lists("ItemID");
        //Grab the items for each pickupItem
        $items = Item::WhereIn("id",$pickupItems)->get();
        //Now let's share this data with the page and show the 4 digit code so we can proceed with the pickup
        $student = User::find($pickup->PersonID);
        View::share("student",$student);
        View::share("items",$items);
        View::share("pickup",$pickup);
        return View::make('admin.pickup.showCode');
    }
}


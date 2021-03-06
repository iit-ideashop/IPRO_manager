<?php
use Http\Url;
use Http\QueryString;

class OrderController extends BaseController {

    public function newOrder($id){

        //We know the project id this order is being created for
        //Let's pull the project id and the user info that we have
        $project = Project::find($id);
        //Make sure that the user is enrolled
        if(!$project->isEnrolled()){
            return Redirect::route('dashboard')->with('error',array('You must be enrolled in the class to place an order'));
        }
        //User is already available
        //Grab the acct info
        $account = $project->Account()->first();
        $enrolledStudents = $project->Users()->where("users.id","!=",Auth::id())->get();
        View::share('project',$project);
        View::share('enrolledStudents',$enrolledStudents);
        View::share('account',$account);
        return View::make('Orders.new');
    }
    
    //Process a new order coming in
    public function newOrderProcess($id){
        //here we will pull the order data that just came in and perform some validation/calculations
        //How to validate an order
        //1. caclulate item totals while validating the items, loop items
        //2.Once totals are calculated attempt an account withdrawl to see if it's possible
        //3. if all passes, create the items in db and save the order, generate an email, etc.
       
        //Pull the inputs first
        $orderPhone = Input::get('Phone');
        $itemNames = Input::get('Names');
        $itemLinks = Input::get('Links');
        $itemPNs = Input::get('PartNumbers');
        $itemCosts = Input::get('Costs');
        $itemQuantities = Input::get('Quantitys');
        $itemShippings = Input::get("shippingCosts");
        $itemJustifications = Input::get('Justifications');
        $itemArray = array();
        $grandTotal = 0;
        $order_has_error = false;
        $order_error = array();

        //Let's make sure we atleast have something submitted
        if(sizeof($itemNames) == 0){
            array_push($order_error, 'Please submit at least one item for ordering');
            $order_has_error = true;
        }
        
        //Let's make sure all of our input arrays are of same size before we start processing the data
        if((sizeof($itemNames) == sizeof($itemLinks)) &&
           (sizeof($itemNames) == sizeof($itemPNs)) &&
           (sizeof($itemNames) == sizeof($itemCosts)) &&
           (sizeof($itemNames) == sizeof($itemQuantities)) &&
            (sizeof($itemNames) == sizeof($itemShippings)) &&
           (sizeof($itemNames) == sizeof($itemJustifications))){
            //Arrays line up, continue
            for($i = 0; $i < sizeof($itemNames); $i++){
                $item = new Item;
                $item->Name = $itemNames[$i];
                $item->Link = $itemLinks[$i];
                if(!filter_var($item->Link,FILTER_VALIDATE_URL)){
                    //Url is invalid
                    array_push($order_error, 'Link "'.$item->Link.'" for '.$item->Name.' is invalid. All links must be in form "http://website.tld".');
                    $order_has_error = true;
                } else {
                    // strip affiliate links, tracking, and other garbage from amazon urls
                    // and utm analytics/tracking from everything else
                    $url = new Url($item->Link);
                    if (strpos($url->host, 'amazon.') !== false) {
                        // amazon does not store useful information in the query string, we can ignore it completely
                        $url->query = NULL;
                    } else {
                        // for non-amazon urls, strip utm tracking information
                        $query_vars = new QueryString();
                        if (!empty($url->query)) {
                            $query_vars->unserialize($url->query);

                            foreach ($query_vars->toArray() as $key => &$value) {
                                if (starts_with($key, array("utm_", ""))) {
                                    $value = '';
                                }
                            }
                            $url->query = $query_vars->toString();
                        }
                    }
                    $item->Link = $url->toString();
                }
                $item->PartNumber = $itemPNs[$i];
                $item->Cost = floatval(str_replace('$','',$itemCosts[$i]));
                $item->Quantity = $itemQuantities[$i];
                $item->Shipping = floatval(str_replace('$','',$itemShippings[$i]));
                $item->TotalCost = ($item->Cost * $item->Quantity) + $item->Shipping;
                $item->Justification = $itemJustifications[$i];
                $item->Status = 1;
                if(!$item->validate()){
                    //Need to make sure this model is validated
                    array_push($order_error, 'Item '.$item->Name.' has errors, please correct them.');
                    $order_has_error = true;
                }
                //Once validated let's add the totalCost to the grand total of the order and also add this item to the items array
                $grandTotal = $grandTotal + $item->TotalCost;
                array_push($itemArray, $item);
            }
        }else{
            $order_has_error = true;
            array_push($order_error, 'The amount of submitted item properties did not match');
        }
        //All items have been validated, we know they will enter db without a problem
        //Next we need to check the account to make sure the account can cover this purchase
        $project = Project::find($id);
        if(!$project->isEnrolled()){
            return Redirect::route('dashboard')->with('error',array('You must be enrolled in the class to place an order.'));
        }
        $account = $project->Account()->first();
        if($grandTotal > $account->Balance){
            //Whoops, a bit over budget
            //Return to page with errors
            $order_has_error = true;
            array_push($order_error, 'Your order is over budget and cannot be created. Please remove some items to remain in budget or request additional funding.');
        }
        $students = $project->Users()->get();
        $enrolledStudents = array();
        foreach($students as $student){
            array_push($enrolledStudents, $student->id);
        }

        //Ok all passed, we have enough funding, and all items are validated, let's create this order
        $order = new Order;
        $order->PeopleID = Auth::id();
        $order->ClassID = $id;
        if($orderPhone != ''){
            $order->Phone = $orderPhone;
        }else{
            $order->Phone = '';
        }
        $order->OrderTotal = $grandTotal;
        $order->Description = Input::get('Description');
        $order->Status = 1;
        if(!$order_has_error){
                if(!$order->save()){
                    $order_has_error = true;
                    array_push($order_error, 'Your order contains errors, please correct them');
                }
        }
        if($order_has_error){
            return Redirect::to('/project/'.$id.'/orders/new')->with('error',$order_error)->with('items',$itemArray);
        }
        //order should now have an id
        //Save all the items
        if(!$order_has_error){
            foreach($itemArray as $item){
                $item->OrderID = $order->id;
                $item->save();
            }
        }
        //all items saved, order created, redirect to project page with success message
        $account->Withdrawl('ORDER',$order->OrderTotal,$order->id);

        $purchasing = DistributionList::where('distributionList', '=', 'protolab_order_create')->pluck('UserID');
        $purchasing_users = User::whereIn('id', $purchasing)->get();
        Mail::send('emails.orderCreate', array('person'=>$order->User()->first(),'order'=>$order,'items'=>$order->Items()->get(), 'project'=>$project), function($message) use ($purchasing_users){
            $message->to(Auth::user()->Email,Auth::user()->getFullName());
            $message->to("ipro@iit.edu", "IPRO");
            foreach ($purchasing_users as $user) {
                $message->bcc($user->Email, $user->getFullName());
            }
            $message->subject('IPRO Order Received!');
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-PreserveRecipients', 'false');
        });
        return Redirect::to('/project/'.$id)->with('success',array('Order successfully created!'));
            
    }

    public function viewOrder($projectid,$orderid){
        //in the function we are going to show the user the order and project. First we confirm the users enrollment in the project.
        $project = Project::find(intval($projectid));
        //Now let's pull the order
        $order = Order::find(intval($orderid));
        //Make sure the order belongs to this project
        if($order->ClassID != $project->id){
            return Redirect::route("dashboard")->with("error",array("You do not have access to that order."));
        }
        //Since we know the user can view the order let's generate the order page
        $orderUser = $order->User()->first();
        $items = $order->Items()->get();
        View::share("project",$project);
        View::share("order_user",$orderUser);
        View::share("order",$order);
        View::share("items",$items);
        return View::make("Orders.view");
    }
}

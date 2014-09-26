<?php


class OrderController extends BaseController {

    public function newOrder($id){
        //We know the project id this order is being created for
        //Let's pull the project id and the user info that we have
        $project = Project::find($id);
        //User is already available
        //Grab the acct info
        $account = $project->Account()->first();
        View::share('project',$project);
        View::share('account',$account);
        return View::make('orders.new');
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
           (sizeof($itemNames) == sizeof($itemJustifications))){
            //Arrays line up, continue
            for($i = 0; $i < sizeof($itemNames); $i++){
                $item = new Item;
                $item->Name = $itemNames[$i];
                $item->Link = $itemLinks[$i];
                $item->PartNumber = $itemPNs[$i];
                $item->Cost = floatval(str_replace('$','',$itemCosts[$i]));
                $item->Quantity = $itemQuantities[$i];
                $item->TotalCost = ($item->Cost * $item->Quantity);
                $item->Justification = $itemJustifications[$i];
                $item->Status = 1;
                if(!$item->validate()){
                    //Need to make sure this model is validated1
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
        $account = $project->Account()->first();
        if($grandTotal > $account->Balance){
            //Whoops, a bit over budget
            //Return to page with errors
            $order_has_error = true;
            array_push($order_error, 'Your order is over budget and cannot be created. Please remove some items to remain in budget.');
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
        //order should now have an id
        //Save all the items
        if(!$order_has_error){
            foreach($itemArray as $item){
                $item->OrderID = $order->id;
                $item->save();
            }
        }
        if($order_has_error){
            return Redirect::to('/project/'.$id.'/orders/new')->with('error',$order_error)->with('items',$itemArray);
        }
        //all items saved, order created, redirect to project page with success message
        $account->Withdrawl('ORDER',$order->OrderTotal,$order->id);
        Mail::send('emails.orderCreate', array('person'=>$order->User()->first(),'order'=>$order,'items'=>$order->Items()->get()), function($message){
            $message->to(Auth::user()->Email,Auth::user()->FirstName.' '.Auth::user()->LastName)->subject('IPRO Order Received!');
        });
        return Redirect::to('/project/'.$id)->with('success',array('Order Successfully created!'));
            
    }
}

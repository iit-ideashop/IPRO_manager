<?php

class AdminItemController extends BaseController{
    
    public function editProcess($id){
        //Edit only the id passed in
        $item = Item::find($id);
        $item->Name = Input::get('Name');
        $item->Link = Input::get('Link');
        $item->PartNumber = Input::get('PartNumber');

        //Let's see if the costs and quantities are changing and if we need to perform a gl entry
        if(($item->Cost != Input::get('Cost')) || ($item->Quantity != Input::get('Quantity')) || ($item->Shipping != Input::get('Shipping'))){
            //Oh noes, we gotta figure out what to do next
            $originalTotalCost = ($item->Cost * $item->Quantity) + $item->Shipping;
            $newTotalCost = (Input::get('Cost') * Input::get('Quantity')) + Input::get('Shipping');
            $amount = abs($originalTotalCost - $newTotalCost);
            //Also pull in the account
            $order = $item->Order()->first();
            $project = $order->Project()->first();
            $account = $project->Account()->first();
            if($originalTotalCost > $newTotalCost){
                $account->Deposit('RECONCILE',$amount,$item->OrderID);
            }elseif($originalTotalCost < $newTotalCost){
                    if(!$account->Withdrawl('RECONCILE',$amount,$item->id)){
                        return Redirect::to('/admin/orders/'.$item->OrderID)->with('error',array('Insufficient funds to cover changes'));
                    }
            }
            $orderNote = new OrderNote;
            $orderNote->OrderID = $item->OrderID;
            $orderNote->ItemID = $item->id;
            $orderNote->Notes = "Item cost has changed from $".number_format($originalTotalCost,2)." to $".  number_format($newTotalCost,2);
            $orderNote->save();
        }
        $item->Cost = Input::get('Cost');
        $item->Quantity = Input::get('Quantity');
        $item->shipping = Input::get('Shipping');
        $item->TotalCost = (Input::get('Cost') * Input::get('Quantity')) + Input::get('Shipping');
        $item->Justification = Input::get('Justification');
        if($item->save()){
            //Save successful
            Order::recalculate($item->OrderID);
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('success',array('Successfully edited order item'));
        }else{
            //Save errors, redirect to the order page or a special item edit page
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('errors',array('Could not edit item'));
        }
    }

    public function markItemReturning($id){
        $item = Item::find($id);
        $item->markReturning();
        if($item->save()){
            //Save successful
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('success',array('Successfully marked item returning'));
        }else{
            //Save errors, redirect to the order page or a special item edit page
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('errors',array('Could not edit item'));
        }
    }
   
    
    public function markItemNotReturning($id){
        $item = Item::find($id);
        $item->Returning = false;
        if($item->save()){
            //Save successful
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('success',array('Successfully marked item not returning'));
        }else{
            //Save errors, redirect to the order page or a special item edit page
            return Redirect::to('/admin/orders/'.$item->OrderID)->with('errors',array('Could not edit item'));
        }
    }
    
    public function massStatusChangeProcess(){
        //This function will change the status for many items within an order
        //Let's see if we can grab the array of items
        $itemArray = json_decode(Input::get('massItemID'));
        if(sizeof($itemArray) == 0){
            return Redirect::to('/admin/orders/')->with('error',array('No items passed to mass status change'));
        }
        $newStatus = Input::get('Status');
        $orderID = null;
        $itemCollection = array();
        foreach($itemArray as $itemID){
            $item = Item::find($itemID);
            array_push($itemCollection,$item);
            }
        //Check that all items have the same order number & other validations
        $orderID = $itemCollection[0]->OrderID;
        foreach($itemCollection as $item){
            if($item->OrderID != $orderID){
                return Redirect::to('/admin/orders/')->with('error',array('Items are from different orders'));
            }
        }
        //Now we need to take each item, update the status, save it, create an email listing and we are in business
        foreach($itemCollection as $item){
            $item->changeStatus($newStatus);
        }
        //After we are done let's update the order
        Order::recalculate($orderID);
        $order = Order::find($orderID);
        $user = $order->User()->first();
        //Prepare the email, different emails based on the status we are updating to. 
        switch($newStatus){
            case 3: // ordered
                Mail::send('emails.orderOrdered', array('person'=>$user,'order'=>$order,'items'=>$itemCollection), function($message) use($user){
                    $message->to($user->Email,$user->FirstName.' '.$user->LastName);
                    $message->subject('IPRO order purchased!');
                });
                break;
            case 4: // received
                Mail::send('emails.orderPickup', array('person'=>$user,'order'=>$order,'items'=>$itemCollection), function($message) use($user){
                    $message->to($user->Email,$user->FirstName.' '.$user->LastName)->subject('IPRO order ready for pickup!');
                });

                break;
            case 5: // picked up
                //Pickup script sends an email, see AdminPickupController
                break;
            case 6: // cancelled
                Mail::send('emails.orderCancelled',
                    array('person'=>$user, 'order'=>$order, 'items'=>$itemCollection),
                    function ($message) use ($user) {
                        $message->to($user->Email, $user->FirstName . ' ' . $user->LastName);
                        $message->subject('IPRO order cancelled');
                    });
                break;
            case 7: // check idea shop stock
                Mail::send('emails.orderCheckStock',
                    array('person'=>$user, 'order'=>$order, 'items'=>$itemCollection),
                    function ($message) use ($user) {
                        $message->to($user->Email, $user->FirstName . ' ' . $user->LastName);
                        $message->subject('IPRO order status');
                    });
                break;
            case 8: // order on hold
                break;
            case 9: // approved for reimbursement
                Mail::send('emails.orderSelfPurchase',
                    array('person'=>$user, 'order'=>$order, 'items'=>$itemCollection),
                    function ($message) use ($user) {
                        $message->to($user->Email, $user->FirstName . ' ' . $user->LastName);
                        $message->subject('IPRO order approved for reimbursement!');
                    });
                break;

        }
        
        return Redirect::to('/admin/orders/'.$orderID)->with('success',array('Successfully changed item statuses'));
    }
    
    public function massMarkReturningProcess(){
        //This function will change the status for many items within an order
        //Let's see if we can grab the array of items
        $itemArray = json_decode(Input::get('massItemID'));
        if(sizeof($itemArray) == 0){
            return Redirect::to('/admin/orders/')->with('error',array('No items passed to mass returning'));
        }
        $orderID = null;
        foreach($itemArray as $itemID){
            $item = Item::find($itemID);
            $orderID = $item->OrderID;
            $item->markReturning();
            $item->save();
        }
        return Redirect::to('/admin/orders/'.$orderID)->with('success',array('Successfully changed item statuses'));
    }
    
    //Delete item code
    public function deleteItem($id){
        //Find the item, delete all notes relating to item
        $item = Item::find($id);
        
        $orderID = $item->OrderID;
        if($item->delete()){
            Order::recalculate($orderID);
            $project = Order::find($orderID)->Project()->first();
            $account = $project->Account()->first();
            $account->Deposit('RECONCILE', (($item->Cost * $item->Quantity) + $item->Shipping), $orderID);
            return Redirect::to('/admin/orders/'.$orderID)->with('success',array('Successfully deleted item'));
        }else{
            return Redirect::to('/admin/orders/'.$orderID)->with('error',array('Unable to delete item'));
        }
    }
    
    public function printLabels(){
        //Someone has initalized a print labels 
        //The process for printint labels is to first get the items we are printing labels for,
        //Take those items and check for barcodes, if no barcode exists we generate one and then
        //save the barcode with the item
        //Next we pass the items over to the print label script and generate the actual labels.
        //Format for input should be passed as a post named items and in array form containing the itemIDs;
        $items = json_decode(Input::get('items'));
        //Loop through the items and print labels
        if(count($items) == 0){
            exit;
        }
        //Grab the item listing from the array
        $itemsCollection = Item::whereIn('id',$items)->get();
        //Loop through the item collection and generate barcodes for items that need them

        foreach($itemsCollection as $item){
            //Check that the item has a barcode

            if($item->barcode == NULL){
                //Make le barcode, and if we make le barcode we should also change the item status to
                $barcode = new Barcode;
                $barcode->type = "ITEM";
                $barcode->reference = $item->id;
                $barcode->save();
                $item->barcode = $barcode->id;
            }

            $item->changeStatus(4);
            $item->save();
        }
        View::share('items',$itemsCollection);
        return View::make('admin.orders.print_label');
    }
}


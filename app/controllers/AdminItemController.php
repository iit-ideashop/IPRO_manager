<?php

class AdminItemController extends BaseController{
    
    public function editProcess($id){
        //Edit only the id passed in
        $item = Item::find($id);
        $item->Name = Input::get('Name');
        $item->Link = Input::get('Link');
        $item->PartNumber = Input::get('PartNumber');
        //Let's see if the costs and quantities are changing and if we need to perform a gl entry
        if(($item->Cost != Input::get('Cost')) || ($item->Quantity != Input::get('Quantity'))){
            //Oh noes, we gotta figure out what to do next
            $originalTotalCost = ($item->Cost * $item->Quantity);
            $newTotalCost = (Input::get('Cost') * Input::get('Quantity'));
            $amount = abs($originalTotalCost - $newTotalCost);
            //Also pull in the account
            $order = $item->Order()->first();
            $project = $order->Project()->first();
            $account = $project->Account()->first();
            if($originalTotalCost > $newTotalCost){
                $account->Deposit('RECONCILE',$amount,$item->id);
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
        $item->TotalCost = (Input::get('Cost') * Input::get('Quantity'));
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
    
    public function statusChangeProcess($id){
        $item = Item::find($id);
        $item->changeStatus(Input::get('Status'));
        
        
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
        foreach($itemArray as $itemID){
            $item = Item::find($itemID);
            $orderID = $item->OrderID;
            $item->changeStatus($newStatus);
            $item->save();
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
    
}


<?php
use LaravelBook\Ardent\Ardent;
class Order extends Ardent {
    
    public function Project(){
        return $this->belongsTo('Project','ClassID');
    }
    public function Items(){
        return $this->hasMany('Item','OrderID');
    }
    public function Notes(){
        return $this->hasMany('OrderNote','OrderID');
    }
    public function User(){
        return $this->belongsTo('User','PeopleID');
    }
    
    public function getStatus(){
        return DB::table('orderStatus')->where('id',$this->Status)->pluck('status');
    }
    
    public function ApprovedPickups(){
        return $this->hasMany('ApprovedPickup','OrderID');
    }
    
    public static function recalculate($id){
        $order = Order::find($id);
        $items = $order->items()->get();
        $newTotal = 0;
        foreach($items as $item){
            $newTotal = $newTotal + $item->TotalCost;
        }
        $order->OrderTotal = $newTotal;
        echo $order->OrderTotal;
        $order->save();
        return true;
    }
    
    public static function updateStatus(){
        //This function will be used to advance the status of the order automatically
        //We essentially need to go out to the database, find all items in the order and look at the item status
        //If all items have been ordered  change the order status to ordered
        //If all items have been delivered, change to Ready for pickup
        //If all items have been picked up change to completed
        
        
    }
    
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }
}


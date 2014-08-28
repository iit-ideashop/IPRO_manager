<?php
use LaravelBook\Ardent\Ardent;
class ApprovedPickup extends Ardent {
    
    public function Order(){
        return $this->belongsTo('Order','OrderID');
    }
    
    public function User(){
        return $this->belongsTo('User','PersonID');
    }
    
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->ApproverID = Auth::id();
    }
}

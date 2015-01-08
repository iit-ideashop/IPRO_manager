<?php
use LaravelBook\Ardent\Ardent;
class Pickup extends Ardent {
    public function User(){
        return $this->belongsTo("User","PersonID");
    }

    public function PickupItems(){
        return $this->hasMany("PickupItem","PickupID");
    }


    public function beforeCreate(){
        //Before we save we have to make sure the modified by is updated
        $this->CreatedBy = Auth::id();
    }
}
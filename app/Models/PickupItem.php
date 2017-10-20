<?php
use LaravelArdent\Ardent\Ardent;
class PickupItem extends Ardent {
    protected $table = 'pickupItems';
    public function Pickup(){
        return $this->belongsTo("Pickup","PickupID");
    }
    public function Item(){
        return $this->hasOne("Item","ItemID");
    }
}


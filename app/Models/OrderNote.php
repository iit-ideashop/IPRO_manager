<?php
use LaravelArdent\Ardent\Ardent;
class OrderNote extends Ardent {
    protected $table = 'orderNotes';
    public function Order(){
        return $this->belongsTo('Order','OrderID');
    }
    
    public function refersTo(){
        return DB::table('items')->where('id',$this->ItemID)->pluck('Name'); 
    }
    
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->EnteredBy = Auth::id();
    }
    
}

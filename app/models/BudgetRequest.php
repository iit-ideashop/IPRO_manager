<?php
use LaravelBook\Ardent\Ardent;
class BudgetRequest extends Ardent {
    protected $table = "budgetRequests";
    
    public function Account(){
        return $this->belongsTo('Account','id','AccountID');
    }
    
    public function scopeActive($query){
        return $query->where('status','=',1);
    }
    
    public function scopeApproved($query){
        return $query->where('status','=',2);        
    }
    
    public function scopeDenied($query){
        return $query->where('status','=',3);        
    }
    public function Requester(){
        return $this->belongsTo('User','Requester','id');
    }
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }
}

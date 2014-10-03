<?php

class BudgetRequest extends Eloquent {
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
    
}

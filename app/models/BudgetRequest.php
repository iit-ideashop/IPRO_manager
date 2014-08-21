<?php

class BudgetRequest extends Eloquent {
    protected $table = "budgetRequests";
    
    public function Account(){
        return $this->belongsTo('Account','id','AccountID');
    }
}

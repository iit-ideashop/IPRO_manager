<?php

class Budget extends Eloquent {
    
    public function Account(){
        return $this->belongsTo('Account','id','AccountID');
    }
    
}


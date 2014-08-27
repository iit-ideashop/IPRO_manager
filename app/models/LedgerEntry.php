<?php

class ledgerEntry extends Eloquent {
    
    protected $table = 'ledgerEntries';
    
    public function Account(){
        return $this->belongsTo('Account','AccountNumber');
    }
    
    
}


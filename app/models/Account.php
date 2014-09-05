<?php
use LaravelBook\Ardent\Ardent;
class Account extends Ardent {
    public static $rules = array(
        'ClassID' => 'required'
    );
    
    public function Project(){
        return $this->belongsTo('Project','SpendingAccount');
    }
    public function Budgets(){
        return $this->hasMany('Budget','AccountID');
    }
    public function GL(){
        return $this->hasMany('LedgerEntry','AccountNumber');
    }
    public function BudgetRequests(){
        return $this->hasMany('BudgetRequest','AccountID');
    }
    
    public function Deposit($entryType,$amount,$entryRef = null){
        //Add moneies to this account
        //Create the GL entry
        $gl = new ledgerEntry;
        $gl->AccountNumber = $this->id;
        $gl->EntryType = $entryType;
        $gl->EntryTypeID = $entryRef;
        $gl->Credit = $amount;
        $gl->Debit = 0;
        $gl->NewAccountBalance = ($this->Balance + $amount);
        $gl->EnteredBy = Auth::id();
        $gl->save();
        $this->Balance = $this->Balance + $amount;
        $this->save();
    }
    
    public function Withdrawl($entryType,$amount,$entryRef = null){
        //Deduct monies from this accuount
        //First let's make sure there are monies available in the account to withdrawl from
        if($this->Balance < $amount){
            return 0;
        }
        $gl = new ledgerEntry;
        $gl->AccountNumber = $this->id;
        $gl->EntryType = $entryType;
        $gl->EntryTypeID = $entryRef;
        $gl->Credit = 0;
        $gl->Debit = $amount;;
        $gl->NewAccountBalance = ($this->Balance - $amount);
        $gl->EnteredBy = Auth::id();
        $gl->save();
        $this->Balance = $this->Balance - $amount;
        $this->save();
        return 1;
    }
    
    public function Recalculate(){
        //Recalculate this account's balance
        
    }
    
}


<?php

class AdminAccountController extends BaseController{

    function showGLEditor($accountID){
        //Pull up the account
        $account = Account::find($accountID);
        //Next pull the account's GL data and show it off
        $gl = $account->GL()->latest()->get();
        //Show the data on the gl form
        View::share('account', $account);
        View::share('gl_data', $gl);
        return View::make('admin.accounts.editor');
    }
    
    function newGLEntry($accountID){
        //We need to create a new account GL entry for the speicified account
        $gl = new ledgerEntry;
        //Grab the posted entry type
        $entryType = Input::get('entryType');
        $allowedTypes = array('RECONCILE','OTHER','REIMBURSEMENT');
        $cd = Input::get('creditdebit');
        $allowedCD = array('CREDIT','DEBIT');
        if(!in_array($entryType, $allowedTypes)){
            return Redirect::to('/admin/accounts/editor/'.$accountID)->with('error',array('That Entry type is not allowed'));
        }
        if(!in_array($cd, $allowedCD)){
            return Redirect::to('/admin/accounts/editor/'.$accountID)->with('error',array('You can only credit or debit an account'));
        }
        //Let's get the dollars
        $money = floatval(Input::get('amount'));
        $money = number_format($money,2);
        $account = Account::find($accountID);
        //Let's make it happen
        if($cd == "CREDIT"){
            //Give the account monies
            //We can just give monies, no need to check if they can go in because they will
            $account->Deposit($entryType,$money);
            return Redirect::to('/admin/accounts/editor/'.$accountID)->with('success',array('Successfully added General Ledger entry'));
        }elseif($cd == "DEBIT"){
            //Take away monies from the account
            if($account->Withdrawl($entryType,$money)){
                //Success!
                return Redirect::to('/admin/accounts/editor/'.$accountID)->with('success',array('Successfully added General Ledger entry'));
            }else{
                return Redirect::to('/admin/accounts/editor/'.$accountID)->with('error',array('Not enough funds to debit account'));
            }
        }
    }
    
}


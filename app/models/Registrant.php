<?php
use LaravelBook\Ardent\Ardent;
class Registrant extends Ardent {
    public static $rules = array(
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email',
    );
    
   public function Registration(){
       return $this->hasMany('Registration','registrant');
   }
   
   
    
}
?>
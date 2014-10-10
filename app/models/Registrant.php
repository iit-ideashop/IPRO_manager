<?php
use LaravelBook\Ardent\Ardent;
class Registrant extends Ardent {
    
   public function Registration(){
       return $this->hasMany('Registration','registrant');
   }
   
   
    
}
?>
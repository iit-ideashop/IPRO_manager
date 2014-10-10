<?php
use LaravelBook\Ardent\Ardent;
class Registration extends Ardent {
    
     public function IPRODay(){
        return $this->belongsTo('IPRODay', 'iproday');
    }
    
    public function Registrant(){
        return $this->belongsTo('Registrant','registrant');
    }
    
}
?>
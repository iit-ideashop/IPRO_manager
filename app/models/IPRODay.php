<?php
use LaravelBook\Ardent\Ardent;
class IPRODay extends Ardent {
    public function Registrations(){
        return $this->hasMany('Registrations','iproday');
    }
    
    public function Tracks(){
        return $this->hasMany('Tracks');
    }
}
?>
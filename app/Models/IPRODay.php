<?php
use LaravelBook\Ardent\Ardent;
class IPRODay extends Ardent {
    protected $table = 'iproday';
    public function Registrations(){
        return $this->hasMany('Registration','iproday');
    }
    
    public function Tracks(){
        return $this->hasMany('Track','iproday');
    }
    
    public function iprotracks(){
        return $this->hasManyThrough('iprotrack', 'Tracks','iproday','trackID');
    }
}
?>
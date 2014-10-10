<?php
use LaravelBook\Ardent\Ardent;
class IPRODay extends Ardent {
    protected $table = 'iproday';
    public function Registrations(){
        return $this->hasMany('Registrations','iproday');
    }
    
    public function Tracks(){
        return $this->hasMany('Tracks','iproday');
    }
    
    public function iprotracks(){
        return $this->hasManyThrough('iprotracks', 'Tracks','iproday','trackID');
    }
}
?>
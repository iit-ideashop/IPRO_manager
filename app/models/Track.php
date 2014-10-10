<?php
use LaravelBook\Ardent\Ardent;
class Track extends Ardent {
    
    public function IPRODay(){
        return $this->belongsTo('IPRODay','iproday');
    }
    
    public function iprotracks(){
        return $this->hasMany('iprotrack','trackID');
    }
    
}
?>
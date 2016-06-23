<?php
use LaravelBook\Ardent\Ardent;
class iprotrack extends Ardent {
    protected $table = 'iprotracks';
    
    public function Track(){
        return $this->belongsTo('Track','trackID');
    }
    
}
?>
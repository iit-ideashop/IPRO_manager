<?php
use LaravelBook\Ardent\Ardent;
class Registration extends Ardent {
    protected $table = 'registration';
    public static $rules = array(
        'type' => 'required',
        'judgedBefore' => 'required|boolean'
    );
     public function IPRODay(){
        return $this->belongsTo('IPRODay', 'iproday');
    }
    
    public function Registrant(){
        return $this->belongsTo('Registrant','registrant');
    }
    
}
?>
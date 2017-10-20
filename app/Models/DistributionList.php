<?php
use LaravelArdent\Ardent\Ardent;
class DistributionList extends Ardent {
    protected $table = 'distribution_lists';
    public static $rules = array(
        'distributionList' => 'required',
        'UserID' => 'required'
    );
    public function Users(){
        return $this->belongsTo('User','UserID','id');
    }
}


<?php
use LaravelBook\Ardent\Ardent;
class Barcode extends Ardent {
    
    public function beforeSave(){
        $this->createdBy = Auth::id();
    }
    
    
    
}


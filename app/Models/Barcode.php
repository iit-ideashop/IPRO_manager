<?php
use LaravelArdent\Ardent\Ardent;
class Barcode extends Ardent {
    
    public function beforeSave(){
        $this->createdBy = Auth::id();
    }
}


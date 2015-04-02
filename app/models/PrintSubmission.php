<?php
use LaravelBook\Ardent\Ardent;
class PrintSubmission extends Ardent {
    protected $table = 'printsubmissions';
    public function getStatus()
    {
        return DB::table('printsubmissionstatuses')->where('id', $this->Status)->pluck('status');
    }

}
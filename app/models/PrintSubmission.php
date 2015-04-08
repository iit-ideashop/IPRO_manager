<?php
use LaravelBook\Ardent\Ardent;
class PrintSubmission extends Ardent {
    protected $table = 'printSubmissions';
    public function getStatus()
    {
        return DB::table('printsubmissionstatuses')->where('id', $this->status)->pluck('status');
    }

}
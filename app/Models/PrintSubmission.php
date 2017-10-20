<?php
use LaravelArdent\Ardent\Ardent;
class PrintSubmission extends Ardent {
    protected $table = 'printSubmissions';
    public function getStatus()
    {
        return DB::table('printSubmissionStatuses')->where('id', $this->status)->pluck('status');
    }

}
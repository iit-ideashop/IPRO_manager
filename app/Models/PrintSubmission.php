<?php
use LaravelArdent\Ardent\Ardent;
/**
 * PrintSubmission
 *
 * @property int $id
 * @property int $UserID
 * @property int|null $pickup_UserID
 * @property int $ProjectID
 * @property string $filename
 * @property string $original_filename
 * @property string $size
 * @property string $dimensions
 * @property int $count_copies
 * @property string $thumb_filename
 * @property string $file_type
 * @property int $override
 * @property int $status
 * @property int|null $barcode
 * @property string $reject_comments
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereCountCopies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereOverride($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission wherePickupUserID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereProjectID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereRejectComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereThumbFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PrintSubmission whereUserID($value)
 * @mixin \Eloquent
 */
class PrintSubmission extends Ardent {
    protected $table = 'printSubmissions';
    public function getStatus()
    {
        return DB::table('printSubmissionStatuses')->where('id', $this->status)->pluck('status');
    }

}
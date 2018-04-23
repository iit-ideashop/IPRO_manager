<?php
use LaravelArdent\Ardent\Ardent;
/**
 * DistributionList
 *
 * @property-read \User $Users
 * @mixin \Eloquent
 * @property int $id
 * @property string $distributionList
 * @property int $UserID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\DistributionList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\DistributionList whereDistributionList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\DistributionList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\DistributionList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\DistributionList whereUserID($value)
 */
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


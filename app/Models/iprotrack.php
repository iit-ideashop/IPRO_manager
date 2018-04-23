<?php
use LaravelArdent\Ardent\Ardent;
/**
 * iprotrack
 *
 * @property-read \Track $Track
 * @mixin \Eloquent
 * @property int $id
 * @property int $trackID
 * @property string $iproNumber
 * @property string $iproName
 * @property string $link
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereIproName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereIproNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereTrackID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\iprotrack whereUpdatedAt($value)
 */
class iprotrack extends Ardent {
    protected $table = 'iprotracks';
    
    public function Track(){
        return $this->belongsTo('Track','trackID');
    }
    
}
?>
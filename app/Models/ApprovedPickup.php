<?php
use LaravelArdent\Ardent\Ardent;
/**
 * ApprovedPickup
 *
 * @property-read \Order $Order
 * @property-read \User $User
 * @mixin \Eloquent
 * @property int $id
 * @property int $PersonID
 * @property int $OrderID
 * @property int $ApproverID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup whereApproverID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup whereOrderID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup wherePersonID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ApprovedPickup whereUpdatedAt($value)
 */
class ApprovedPickup extends Ardent {
    protected $table = 'approvedPickups';

    public function Order(){
        return $this->belongsTo('Order','OrderID');
    }
    
    public function User(){
        return $this->belongsTo('User','PersonID');
    }
    
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->ApproverID = Auth::id();
    }
}


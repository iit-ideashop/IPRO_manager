<?php
use LaravelArdent\Ardent\Ardent;
/**
 * Pickup
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\PickupItem[] $PickupItems
 * @property-read \User $User
 * @mixin \Eloquent
 * @property int $id
 * @property int $PersonID
 * @property int|null $RetreiveCode
 * @property int|null $AuthorizeCode
 * @property int $Completed
 * @property string|null $SignatureData
 * @property string|null $OverrideReason
 * @property int $CreatedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereAuthorizeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereOverrideReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup wherePersonID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereRetreiveCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereSignatureData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Pickup whereUpdatedAt($value)
 */
class Pickup extends Ardent {
    public function User(){
        return $this->belongsTo("User","PersonID");
    }

    public function PickupItems(){
        return $this->hasMany("PickupItem","PickupID");
    }


    public function beforeCreate(){
        //Before we save we have to make sure the modified by is updated
        $this->CreatedBy = Auth::id();
    }
}
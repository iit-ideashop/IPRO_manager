<?php
use LaravelArdent\Ardent\Ardent;
/**
 * PickupItem
 *
 * @property-read \Item $Item
 * @property-read \Pickup $Pickup
 * @mixin \Eloquent
 * @property int $id
 * @property int $ItemID
 * @property int $PickupID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PickupItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PickupItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PickupItem whereItemID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PickupItem wherePickupID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PickupItem whereUpdatedAt($value)
 */
class PickupItem extends Ardent {
    protected $table = 'pickupItems';
    public function Pickup(){
        return $this->belongsTo("Pickup","PickupID");
    }
    public function Item(){
        return $this->hasOne("Item","ItemID");
    }
}


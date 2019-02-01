<?php
use LaravelArdent\Ardent\Ardent;
/**
 * Order
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ApprovedPickup[] $ApprovedPickups
 * @property-read \Illuminate\Database\Eloquent\Collection|\Item[] $Items
 * @property-read \Illuminate\Database\Eloquent\Collection|\OrderNote[] $Notes
 * @property-read \Project $Project
 * @property-read \User $User
 * @mixin \Eloquent
 * @property int $id
 * @property int $PeopleID
 * @property int $ClassID
 * @property string|null $Phone
 * @property float $OrderTotal
 * @property string $Description
 * @property string $NotificationPreference
 * @property int $Status
 * @property int $ModifiedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereClassID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereNotificationPreference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereOrderTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order wherePeopleID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Order whereUpdatedAt($value)
 */
class Order extends Ardent
{

    public function Project()
    {
        return $this->belongsTo('Project', 'ClassID');
    }

    public function Items()
    {
        return $this->hasMany('Item', 'OrderID');
    }

    public function Notes()
    {
        return $this->hasMany('OrderNote', 'OrderID');
    }

    public function User()
    {
        return $this->belongsTo('User', 'PeopleID');
    }

    public function getStatus()
    {
        return DB::table('orderStatus')->where('id', $this->Status)->value('status');
    }

    public function ApprovedPickups()
    {
        return $this->hasMany('ApprovedPickup', 'OrderID');
    }

    public static function recalculate($id)
    {
        $order = Order::find($id);
        $items = $order->items()->get();
        $newTotal = 0;
        foreach ($items as $item) {
            $newTotal = $newTotal + $item->TotalCost;
        }
        $order->OrderTotal = $newTotal;
        echo $order->OrderTotal;
        $order->save();
        return true;
    }

    /**
     * @param $orderid
     */
    public static function updateStatus($orderid)
    {
        //This function will be used to advance the status of the order automatically
        //We essentially need to go out to the database, find all items in the order and look at the item status
        //If atleast 1 item has been ordered  change the order status to ordered
        //If atleast 1 item has been delivered, change to Ready for pickup
        //If all items have been picked up change to completed
        //If all the items in the order are cancelled then mark the order complete

        //Pull all of the items in the order
        $order = Order::find(intval($orderid));
        //$items is a collection of items, we can loop through it
        $items = $order->items()->get();
        $ordered = 0;
        $delivered = 0;
        $complete = 0;
        $totalitems = 0;
        foreach ($items as $item) {
            //If ordered
            if ($item->Status == 3) {
                $ordered++;
            }
            //If delivered
            if ($item->Status == 4) {
                $delivered++;
            }
            //If picked up or cancelled
            if (($item->Status == 5) || ($item->Status == 6)) {
                $complete++;
            }
            $totalitems++;
        }
        //We counted all of the items, now to update the status
        if ($ordered > 0) {
            $order->Status = 2;
        }
        if ($delivered > 0) {
            $order->Status = 3;////
        }
        if ($complete == $totalitems) {
            $order->Status = 4;
        }
        $order->save();
    }

    public function beforeSave()
    {
        //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }

}

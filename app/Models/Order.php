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
        // This function will be used to advance the status of the order automatically
        // We essentially need to go out to the database, find all items in the order and look at the item status
        // If all items are either denied or picked up, order is complete
        // If any items are Check Idea Shop Stock or Check Buying Restrictions, order is awaiting followup
        // If all items are either denied or received, the order is ready for pickup
        // If any items are ordered or received (but not all received), the order is ordered
        // Otherwise, the order is requested (Should only be the case if all items are requested)

        //Pull all of the items in the order
        $order = Order::find(intval($orderid));
        //$items is a collection of items, we can loop through it
        $items = $order->items()->get();
        $ordered = 0;
        $delivered = 0;
        $complete = 0;
        $denied = 0;
        $needsAttention = 0;
        $totalitems = 0;
        foreach ($items as $item) {
            // If ordered
            if ($item->Status == 3) {
                $ordered++;
            }
            // If delivered
            elseif ($item->Status == 4) {
                $delivered++;
            }
            // If picked up or approved for reimbursement
            elseif (($item->Status == 5) || ($item->Status == 2)) {
                $complete++;
            }
            // Denied
            elseif ($item->Status == 6) {
                $denied++;
            }
            // Check Idea Shop Stock, Check Buying Restrictions
            elseif (($item->Status == 7) || ($item->Status == 8)) {
                $needsAttention++;
            }
            $totalitems++;
        }
        $nondenied = $totalitems - $denied;

        //We counted all of the items, now to update the status

        // Also matches the case where all orders were denied, which we want to be considered "Completed" as well
        if ($complete == $nondenied) {
            $order->Status = 4; // Completed
        }
        elseif ($needsAttention > 0) {
            $order->Status = 5; // Awaiting Followup
        }
        elseif ($delivered == $nondenied) {
            $order->Status = 3; // Ready for pickup
        }
        // Don't go back to requested if all orders are either delivered or requested
        elseif ($ordered > 0 || $delivered > 0) {
            $order->Status = 2; // Ordered
        }
        else {
            $order->Status = 1; // Requested
        }
        $order->save();
    }

    public function beforeSave()
    {
        //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }

}

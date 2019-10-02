<?php
use LaravelArdent\Ardent\Ardent;
/**
 * Item
 *
 * @property int $id
 * @property int $OrderID
 * @property string $Name
 * @property string $Link
 * @property string|null $PartNumber
 * @property float $Cost
 * @property int $Quantity
 * @property float $Shipping
 * @property float $TotalCost
 * @property string $Justification
 * @property int $Returning
 * @property int $Status
 * @property int|null $barcode
 * @property int $ModifiedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Order $Order
 * @property-read \PickupItem $PickupItem
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereJustification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereOrderID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item wherePartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereReturning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Item whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Item extends Ardent {
    public static $rules = array(
        'Name' => array('required'),
        'Link' => array('required'),
        'Cost' => array('required','numeric'),
        'Quantity'=>array('required','numeric'),
        'TotalCost'=>array('required','numeric'),
        'Justification'=>array('required'),
    );
    
    public function Order(){
        return  $this->belongsTo('Order','OrderID');
    }
    
    public function getStatus(){
     return DB::table('itemStatus')->where('id',$this->Status)->value('status');
    }

    public function PickupItem(){
        return $this->belongsTo("PickupItem","ItemID");
    }

    public function changeStatus($newStatusID){
        $this->Status = $newStatusID;
        //Here we will add functionality to process status changes
        //Statuses
        //1 - Requested, nothing here
        //2 - Approved for Reimbursement - nothing here
        //3 - Ordered - Send an email to the user on the order that it has been ordered
        //4 - Received - Send email that item is here and ready for pickup
        //5 - Picked up - Our Pickup Controller will take care of sending the final pickup email
        //6 - Denied, we have to make the item worth $0 and update the order
        //7 - Check Idea Shop Stock - Send an email to the user that they should check with Idea Shop staff before
        //                            continuing since we might have that or a similar item
        //8 - Check Buying Restrictions - Send an email to the user saying that they should contact ipro for more information
        $orderNote = new OrderNote;
        $orderNote->OrderID = $this->OrderID;
        $orderNote->ItemID = $this->id;
        
        switch(Input::get('Status')){
         case 3:
             $orderNote->Notes = 'Item Purchased';
             break;
         case 4:
             $orderNote->Notes = 'Item Received';
             break;
         case 5:
             $orderNote->Notes = 'Item Picked up';
             break;
         case 6:
             $orderNote->Notes = 'Status Changed to '.$this->getStatus().'  Cost: $'.number_format($this->Cost,2);
             //We need to perform a refund for status 6
             $order = Order::find($this->OrderID);
             $project = $order->Project()->first();
             $account = $project->Account()->first();
             $account->Deposit('RECONCILE',($this->Cost * $this->Quantity), $this->id);
             $this->Cost = 0;
             $this->TotalCost = 0;
             break;
         default:
             $orderNote->Notes = 'Status changed to '.$this->getStatus();
        }
        $this->save();
        $orderNote->save();
        
    }
    public function markReturning(){
        $this->Returning = true;
        $orderNote = new OrderNote;
        $orderNote->OrderID = $this->OrderID;
        $orderNote->ItemID = $this->id;
        $orderNote->Notes = 'Item marked as Returning';
        $orderNote->save();
    }
    
    public function beforeSave(){
     //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }

    public function afterSave(){
        Order::updateStatus($this->OrderID);
    }
    
    public function deleteItem(){
        $itemNotes = OrderNote::where('ItemID','=',$this->id)->get();
        foreach($itemNotes as $itemNote){
            $itemNote->delete();
        }
        $this->delete();
    }
}

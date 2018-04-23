<?php

use LaravelArdent\Ardent\Ardent;

/**
 * LedgerEntry
 *
 * @property int $id
 * @property int $AccountNumber
 * @property string $EntryType
 * @property int|null $EntryTypeID
 * @property float $Credit
 * @property float $Debit
 * @property float $NewAccountBalance
 * @property int $EnteredBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Account $Account
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereEnteredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereEntryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereEntryTypeID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereNewAccountBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\LedgerEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LedgerEntry extends Ardent {
    
    protected $table = 'ledgerEntries';
    
    public function Account(){
        return $this->belongsTo('Account','AccountNumber');
    }
    
    
}


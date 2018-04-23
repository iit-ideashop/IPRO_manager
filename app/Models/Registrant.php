<?php
use LaravelArdent\Ardent\Ardent;
/**
 * Registrant
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $organization
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Registration[] $Registration
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Registrant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Registrant extends Ardent {
    public static $rules = array(
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email',
    );
    
   public function Registration(){
       return $this->hasMany('Registration','registrant');
   }
   
   
    
}
?>
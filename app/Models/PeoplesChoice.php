<?php
use LaravelArdent\Ardent\Ardent;
/**
 * PeoplesChoice
 *
 * @property int $id
 * @property string $FirstName
 * @property string $LastName
 * @property string $idnumber
 * @property string $IDType
 * @property int $ProjectID
 * @property int $Semester
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereIDType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereIdnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereProjectID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PeoplesChoice extends Ardent {

    protected $table = 'peoplesChoice';
}


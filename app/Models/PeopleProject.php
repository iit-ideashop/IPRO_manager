<?php
use LaravelArdent\Ardent\Ardent;
/**
 * PeopleProject
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $UserID
 * @property int $ClassID
 * @property int $AccessType
 * @property int $ModifiedBy
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereAccessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereClassID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeopleProject whereUserID($value)
 */
class PeopleProject extends Ardent {
    protected $table = "PeopleProjects";
    protected $fillable = ["UserId", "ClassId"];

    public function beforeSave(){
        //Before we save we have to make sure the modified by is updated
        $this->ModifiedBy = Auth::id();
    }
}


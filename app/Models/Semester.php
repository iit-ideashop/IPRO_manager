<?php
use LaravelArdent\Ardent\Ardent;
/**
 * Semester
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Project[] $Projects
 * @property-read \User $modifiedBy
 * @mixin \Eloquent
 * @property int $id
 * @property string $Name
 * @property int $Active
 * @property string $ActiveStart
 * @property string $ActiveEnd
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereActiveEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereActiveStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereModifiedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Semester whereUpdatedAt($value)
 */
class Semester extends Ardent {
    public static $rules = array(
        'Name' => array('required'),
        
        'ActiveStart' => array('required'),
        'ActiveEnd' => array('required'),
        'modifiedBy' => array('required')
    );
    
    public function Projects(){
        return $this->hasMany('Project','Semester');
    }
    public function modifiedBy(){
        return $this->belongsTo('User','id','modifiedBy');
    }
    
    public function makeActive(){
        //makes this semester the active semester
        DB::table('semesters')->update(array('Active'=>0));
        $this->Active = true;
        $this->save();
    }
    
    
    
}


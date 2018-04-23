<?php
use LaravelArdent\Ardent\Ardent;
/**
 * PeoplesChoiceTracks
 *
 * @property int $id
 * @property int $TrackNumber
 * @property int $ProjectID
 * @property int $Semester
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereProjectID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereTrackNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PeoplesChoiceTracks whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PeoplesChoiceTracks extends Ardent {
    protected $table = 'peoplesChoiceTracks';

}


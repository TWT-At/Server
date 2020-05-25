<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MeetingAttendee
 *
 * @property int $id
 * @property int $meeting_id
 * @property int $user_id
 * @property string|null $name
 * @property int $status
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MeetingAttendee whereUserId($value)
 * @mixin \Eloquent
 */
class MeetingAttendee extends Model
{
    public $table="meeting_attendee";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["meeting_id","user_id","name","status"];
}

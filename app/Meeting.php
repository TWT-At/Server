<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Meeting
 *
 * @property int $id
 * @property int $user_id
 * @property string $topic
 * @property string $BeginTime
 * @property string $EndTime
 * @property string $DateTime
 * @property string $campus
 * @property string|null $attend_user
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereAttendUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereBeginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Meeting whereUserId($value)
 * @mixin \Eloquent
 */
class Meeting extends Model
{
    public $table="meeting";
    public $primaryKey="id";
    public $fillable=["user_id","BeginTime","EndTime","DateTime","campus","topic","attend_user"];
    public $timestamps=false;
}

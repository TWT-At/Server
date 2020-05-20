<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WeekPublicationLoveComment
 *
 * @property int $id
 * @property int $to_comment_id
 * @property int $user_id
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment whereToCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationLoveComment whereUserId($value)
 * @mixin \Eloquent
 */
class WeekPublicationLoveComment extends Model
{
    public $table="weekpublicationlovecomment";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["to_comment_id","user_id"];
}

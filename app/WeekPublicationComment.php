<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WeekPublicationComment
 *
 * @property int $id
 * @property int $to_publication_id
 * @property string $to_author
 * @property string $name
 * @property string $comment
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereToAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereToPublicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationComment whereUpdateAt($value)
 * @mixin \Eloquent
 */
class WeekPublicationComment extends Model
{
    public $table="weekpublicationcomment";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["to_publication_id","to_author","name","comment"];
}

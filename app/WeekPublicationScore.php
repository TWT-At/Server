<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WeekPublicationScore
 *
 * @property int $id
 * @property int $WeekPublication_id
 * @property string $author
 * @property string $scorer
 * @property int $score
 * @property string $comment
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereScorer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WeekPublicationScore whereWeekPublicationId($value)
 * @mixin \Eloquent
 */
class WeekPublicationScore extends Model
{
    public $table="weekpublicationscore";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["WeekPublication_id","author","scorer","score","comment"];
}

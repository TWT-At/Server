<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Announce
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $post_group
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce wherePostGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Announce whereUpdateAt($value)
 * @mixin \Eloquent
 */
class Announce extends Model
{
    protected $table="announce";
    protected $primaryKey="id";
    protected $fillable=["title","post_group","content"];

    public $timestamps=false;
}

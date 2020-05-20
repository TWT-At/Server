<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CloudDriver
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $filenewname
 * @property string|null $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereFilenewname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CloudDriver whereUserId($value)
 * @mixin \Eloquent
 */
class CloudDriver extends Model
{
    public $table="clouddriver";
    public $primaryKey="id";
    public $fillable=["user_id","filename","filenewname","created_at"];
    public $timestamps=false;
}

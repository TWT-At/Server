<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProjectLog
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectLog whereUpdateAt($value)
 * @mixin \Eloquent
 */
class ProjectLog extends Model
{
    protected $table="projectlog";
    protected $primaryKey="id";
    protected $fillable=["project_id","name","description"];
    public $timestamps=false;
}

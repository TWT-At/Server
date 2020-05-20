<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Task
 *
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $process
 * @property string $deadline
 * @property string $created_at
 * @property string $update_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereUpdateAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Task withoutTrashed()
 * @mixin \Eloquent
 */
class Task extends Model
{
    use SoftDeletes;
    protected $table="task";
    protected $primaryKey="id";
    //protected $dates=["deleted_at"];
    protected $fillable=["project_id","name","title","description","process","deadline"];

    public $timestamps=false;
}

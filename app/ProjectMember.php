<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProjectMember
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $name
 * @property string $group_name
 * @property int $permission
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectMember whereUserId($value)
 * @mixin \Eloquent
 */
class ProjectMember extends Model
{
    protected $primaryKey="id";
    protected $table="projectmember";
    protected $fillable=["project_id","user_id","name","permission","group_name"];

    public $timestamps=false;
}

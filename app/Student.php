<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Student
 *
 * @property int $id
 * @property string $student_id
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $group_name
 * @property string $group_role
 * @property string $campus
 * @property int|null $permission
 * @property string|null $avatar
 * @property string $created_at
 * @property string $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereGroupRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Student whereUpdateAt($value)
 * @mixin \Eloquent
 */
class Student extends Model
{
    protected $table='student';
    protected $primaryKey="id";
    protected $fillable=["student_id","name","group_name","group_role","campus","email","password","avatar"];

    public $timestamps=false;
}

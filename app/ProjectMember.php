<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $primaryKey="id";
    protected $table="projectmember";
    protected $fillable=["project_id","user_id","name","permission","group_name"];

    public $timestamps=false;
}

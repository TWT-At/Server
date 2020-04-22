<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    protected $table="projectlog";
    protected $primaryKey="id";
    protected $fillable=["project_id","name","description"];
    public $timestamps=false;
}

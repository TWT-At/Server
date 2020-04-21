<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    protected $table="projectlog";
    protected $primaryKey="id";

    public $timestamps=false;
}

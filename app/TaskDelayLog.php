<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskDelayLog extends Model
{
    public $table="task_delay_log";
    public $timestamps=false;
    public $primaryKey="id";
    public $fillable=["project_id","task_id","old_ddl","new_ddl"];
}

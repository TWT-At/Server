<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $table="task";
    protected $primaryKey="id";
    //protected $dates=["deleted_at"];
    protected $fillable=["project_id","name","title","description","process"];

    public $timestamps=false;
}

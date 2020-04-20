<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table="task";
    protected $primaryKey="id";
    protected $fillable=["project_id","name","title","description","process"];

    public $timestamps=false;
}

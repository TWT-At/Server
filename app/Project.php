<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table="project";
    protected $primaryKey="id";
    protected $fillable=["author_id","name","title","description","permission"];//白名单

    public $timestamps=false;
}

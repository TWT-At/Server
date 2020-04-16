<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table='student';
    protected $primaryKey="id";
    protected $fillable=["student_id","name","group","group_role","campus","email","password"];

    public $timestamps=false;
}

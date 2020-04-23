<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table='student';
    protected $primaryKey="id";
    protected $fillable=["student_id","name","group_name","group_role","campus","email","password","avatar"];

    public $timestamps=false;
}

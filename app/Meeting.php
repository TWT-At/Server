<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public $table="meeting";
    public $primaryKey="id";
    public $fillable=["user_id","BeginTime","EndTime","DateTime","campus","topic","attend_user"];
    public $timestamps=false;
}

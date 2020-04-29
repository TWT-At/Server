<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table="message";
    protected $primaryKey="id";
    protected $fillable=["user_id","type","title","message","read"];

    public $timestamps=false;
}

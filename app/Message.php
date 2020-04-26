<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table="message";
    protected $primaryKey="id";
    protected $fillable=["user_id","message"];

    public $timestamps=false;
}

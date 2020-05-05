<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announce extends Model
{
    protected $table="announce";
    protected $primaryKey="id";
    protected $fillable=["title","post_group","content"];

    public $timestamps=false;
}

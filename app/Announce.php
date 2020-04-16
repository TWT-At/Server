<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announce extends Model
{
    protected $table="announce";
    protected $primaryKey="id";


    public $timestamps=false;
}

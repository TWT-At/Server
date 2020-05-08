<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekPublicationScore extends Model
{
    public $table="weekpublicationscore";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["WeekPublication_id","author","scorer","score"];
}

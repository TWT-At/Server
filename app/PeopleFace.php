<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeopleFace extends Model
{
    public $table = "people_face";
    public $primaryKey = "id";
    public $timestamps = false;
    public $fillable = ["user_id", "image_name"];
}

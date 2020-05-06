<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CloudDriver extends Model
{
    public $table="clouddriver";
    public $primaryKey="id";
    public $fillable=["user_id","filename","filenewname","created_at"];
    public $timestamps=false;
}

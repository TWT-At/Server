<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekPublicationLoveComment extends Model
{
    public $table="weekpublicationlovecomment";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["to_comment_id","user_id"];
}

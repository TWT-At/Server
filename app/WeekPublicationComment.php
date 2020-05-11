<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeekPublicationComment extends Model
{
    public $table="weekpublicationcomment";
    public $primaryKey="id";
    public $timestamps=false;
    public $fillable=["to_publication_id","to_author","name","comment"];
}

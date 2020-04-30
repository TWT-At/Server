<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table="Permission";
    protected $primaryKey="id";
    protected $fillable=["站长","组长","组员","实习生","骨灰"];
    public $timestamps=false;
}

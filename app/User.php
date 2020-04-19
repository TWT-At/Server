<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'name', 'email', 'password','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /*JWT*/
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 获取将存储在JWT的中的标识符token。
     * @return mixed
     */
    /*public function getJWTIdentifier()
    {
        return $this->getKey();
    }*/

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * 返回一个键值数组，其中包含要添加到JWT中的任何自定义声明
     * @return array
     */
    /*public function getJWTCustomClaims()
    {
        return [];
    }*/
}

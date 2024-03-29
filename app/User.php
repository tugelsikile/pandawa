<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'isp_user';
    protected $fillable = [
        'name', 'email', 'password', 'cab_id',
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
        'created' => 'datetime',
    ];

    public function userLevelOjb(){
        return $this->belongsTo(UserLevel::class,'level');
    }
    public function cabangObj(){
        return $this->belongsTo(Cabang::class,'cab_id');
    }
    protected $with = ['cabangObj','userLevelOjb'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model{
    public $table = 'isp_user_level';

    public function userObj(){
        return $this->hasMany(User::class,'level');
    }
    public function userPriviledgesObj(){
        return $this->hasMany(UserPriviledges::class,'lvl_id','lvl_id');
    }
}
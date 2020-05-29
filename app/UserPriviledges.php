<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPriviledges extends Model{
    public $table = 'isp_user_priviledges';

    public function userLevelObj(){
        return $this->belongsTo(UserLevel::class,'lvl_id','lvl_id');
    }
}
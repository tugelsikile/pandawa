<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model{
    public $table = 'isp_controllers';

    public function userPriviledgesObj(){
        return $this->belongsTo(UserPriviledges::class,'ctrl_id','ctrl_id');
    }
}
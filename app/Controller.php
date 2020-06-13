<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model{
    public $table = 'isp_controllers';
    protected $primaryKey = 'ctrl_id';

    public function functionObj(){
        return $this->hasMany(Functions::class,'ctrl_id');
    }
    public function userPriviledgesObj(){
        return $this->belongsTo(UserPriviledges::class,'ctrl_id','ctrl_id');
    }
}
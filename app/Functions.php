<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Functions extends Model
{
    protected $table = 'isp_functions';
    public $timestamps = false;
    protected $primaryKey = 'func_id';

    public function controllerObj(){
        return $this->belongsTo(Controller::class,'ctrl_id');
    }
}

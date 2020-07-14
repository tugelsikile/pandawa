<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cabang extends Model
{
    protected $primaryKey = 'cab_id';
    protected $table = 'isp_cabang';
    public $timestamps = false;

    public function customerObj(){
        return $this->hasMany(Customer::class,'cab_id')->where('is_active','=',1)->where('status','=',1);
    }
}

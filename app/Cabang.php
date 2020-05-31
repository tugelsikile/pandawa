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
        return $this->hasMany(CabangMember::class,'cab_id');
    }
}

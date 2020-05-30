<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cabang extends Model
{
    public $primaryKey = 'cab_id';
    protected $table = 'isp_cabang';

    public function customerObj(){
        return $this->hasMany(CabangMember::class,'cab_id');
    }
}

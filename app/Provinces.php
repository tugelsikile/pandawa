<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    public $table = 'isp_region_provinces';
    public $primaryKey = 'id';

    public function kabObj(){
        return $this->hasMany(Kabupaten::class,'province_id','id');
    }
}

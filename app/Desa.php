<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    public $table = 'isp_region_villages';
    public function kecObj(){
        return $this->belongsTo(Kecamatan::class,'district_id','id');
    }
}

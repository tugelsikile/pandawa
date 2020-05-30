<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    public $table = 'isp_region_regencies';
    public $primaryKey = 'id';

    public function provObj(){
        return $this->belongsTo(Provinces::class,'id','province_id');
    }
    public function kecObj(){
        return $this->hasMany(Kecamatan::class,'id','regency_id');
    }
}

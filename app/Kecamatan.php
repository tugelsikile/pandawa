<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    public $table = 'isp_region_districts';
    public $primaryKey = 'id';

    public function kabObj(){
        return $this->belongsTo(Kabupaten::class,'id','regency_id');
    }
    public function desaObj(){
        return $this->hasMany(Desa::class,'id','district_id');
    }

}

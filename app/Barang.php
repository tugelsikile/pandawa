<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = 'br_id';
    protected $table = 'isp_barang';
    public $timestamps = false;

    public function originObj(){
        return $this->belongsTo(Cabang::class,'origin','cab_id');
    }
    public function locationObj(){
        return $this->belongsTo(Cabang::class,'cab_id','location');
    }
}

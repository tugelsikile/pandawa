<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'cust_id';
    protected $table = 'isp_customer';
    public $timestamps = false;

    public function cabangObj(){
        return $this->belongsTo(Cabang::class,'cab_id')->where('status','=',1);
    }
    public function paketObj(){
        return $this->belongsTo(Produk::class,'pac_id','pac_id')->where('status','=',1);
    }
    public function desa(){
        return $this->hasOne(Desa::class,'id','village_id');
    }
    public function kecamatan(){
        return $this->hasOne(Kecamatan::class,'id','district_id');
    }
    public function kabupaten(){
        return $this->hasOne(Kabupaten::class,'id','regency_id');
    }
    public function provinsi(){
        return $this->hasOne(Provinces::class,'id','province_id');
    }
    public function pasang_desa(){
        return $this->hasOne(Desa::class,'id','pas_village_id');
    }
    public function pasang_kecamatan(){
        return $this->hasOne(Kecamatan::class,'id','pas_district_id');
    }
    public function pasang_kabupaten(){
        return $this->hasOne(Kabupaten::class,'id','pas_regency_id');
    }
    public function pasang_provinsi(){
        return $this->hasOne(Provinces::class,'id','pas_province_id');
    }
    public function produk(){
        return $this->hasOne(Produk::class,'pac_id','pac_id');
    }
}

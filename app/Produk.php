<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $primaryKey = 'pac_id';
    protected $table = 'isp_package';
    public $timestamps = false;

    public function customerObj(){
        return $this->hasMany(Customer::class,'pac_id')->where('status','=',1);
    }
    public function cabang(){
        return $this->hasOne(Cabang::class,'cab_id','cab_id')->where('status','=',1);
    }
    public function invoice(){
        return $this->hasMany(Invoice::class,'pac_id','pac_id')->where('status','=',1);
    }
}

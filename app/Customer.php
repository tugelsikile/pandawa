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
}

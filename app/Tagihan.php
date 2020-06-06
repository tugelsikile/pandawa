<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'isp_invoice';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'inv_id';

    public function paketObj(){
        return $this->belongsTo(Produk::class,'pac_id');
    }
    public function cabangObj(){
        return $this->belongsTo(Cabang::class,'cab_id');
    }
    public function customerObj(){
        return $this->belongsTo(Customer::class,'cust_id');
    }
}

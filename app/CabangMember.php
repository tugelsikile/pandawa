<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CabangMember extends Model
{
    protected $table = 'isp_cabang_member';
    public $primaryKey = 'icm_id';

    public function customerObj(){
        return $this->belongsTo(Customer::class,'cust_id');
    }
    public function cabangObj(){
        return $this->belongsTo(Cabang::class,'cab_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    protected $table = 'isp_jenis_layanan';
    public function customer(){
        return $this->hasMany(Customer::class,'jenis_layanan','id')->where('status','=',1);
    }
}

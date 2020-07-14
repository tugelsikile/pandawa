<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey   = 'inv_id';
    protected $table        = 'isp_invoice';
    public $timestamps      = false;
    public function cabang(){
        return $this->hasMany(Cabang::class,'cab_id')->where('status','=',1);
    }
    public function customer(){
        return $this->hasMany(Customer::class,'cab_id')->where('status','=',1);
    }
}

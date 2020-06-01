<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $primaryKey = 'pac_id';
    protected $table = 'isp_package';
    public $timestamps = false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey   = 'inv_id';
    protected $table        = 'isp_invoice';
    public $timestamps      = false;
}

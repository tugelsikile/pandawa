<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'isp_bank_account';
    protected $primaryKey = 'bank_id';
    public $timestamps = false;
}

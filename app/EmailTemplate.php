<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'isp_mail_template';
    protected $primaryKey = 'tmp_id';
    public $timestamps = false;
}

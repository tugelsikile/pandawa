<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $table = 'isp_mail_setting';
    public $timestamps = false;
    protected $primaryKey = 'ms_id';
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserPriviledgesRepositories{
    public function checkPrivs($level,$url){
        $url = str_replace('-','_',$url);
        $data = DB::select("
            SELECT    iup.priv_id,iup.R_opt,iup.C_opt,iup.U_opt,iup.D_opt
            FROM      isp_user_priviledges AS iup
            LEFT JOIN isp_controllers AS ic ON iup.ctrl_id = ic.ctrl_id
            WHERE     iup.lvl_id = ".$level." AND ic.ctrl_url = '".$url."'
        ");
        if (!is_null($data)){
            return $data[0];
        }
    }
}
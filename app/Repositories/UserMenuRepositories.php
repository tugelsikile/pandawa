<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserMenuRepositories{
    public function getMenu($user_level){
        $data = DB::select("
            SELECT    iup.*,ic.*,isf.*
            FROM      isp_user_priviledges AS iup
            LEFT JOIN isp_controllers AS ic ON iup.ctrl_id = ic.ctrl_id
            LEFT JOIN isp_functions AS isf ON iup.func_id = isf.func_id
            WHERE     iup.lvl_id = '$user_level' AND iup.R_opt = 1
        ");
        $datas = [];
        if (!is_null($data)){
            foreach ($data as $key => $val){
                $exists = collect($datas)->where('ctrl_refs',$val->ctrl_refs);
                if ($exists->isEmpty()){
                    $val->ctrl_url = str_replace('_','-',$val->ctrl_url);
                    $datas[$key] = $val;
                }
            }
        }
        return $datas;
    }
}
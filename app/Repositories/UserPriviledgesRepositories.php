<?php

namespace App\Repositories;

use App\UserPriviledges;
use Illuminate\Support\Facades\DB;

class UserPriviledgesRepositories{
    public function getAllBy($param){
        return UserPriviledges::where($param)->get();
    }
    public function checkPrivs($level,$url){
        $url = str_replace('-','_',$url);
        //DB::enableQueryLog();
        $data = DB::table('isp_user_priviledges','ip')
            ->select('priv_id','R_opt','C_opt','U_opt','D_opt')
            ->join('isp_controllers','ip.ctrl_id','=','isp_controllers.ctrl_id','left')
            ->where('ip.lvl_id',$level)
            ->where('isp_controllers.ctrl_url','like',"%$url%")
            ->get()->first();
        //dd(DB::getQueryLog());
        if (is_null($data)){
            return false;
        } else {
            return $data;
        }
    }
}
<?php

function checkPrivileges($controller=false,$function=false){
    if (!$controller && !$function){
        return false;
    } else {
        $lvl    = \Illuminate\Support\Facades\Auth::user()->level;
        $controller = str_replace('-','_',$controller);
        $function   = str_replace('-','_',$function);
        $data   = \Illuminate\Support\Facades\DB::table('isp_user_priviledges')
            ->select('priv_id','R_opt','C_opt','U_opt','D_opt')
            ->join('isp_controllers','isp_user_priviledges.ctrl_id','=','isp_controllers.ctrl_id','left')
            ->join('isp_functions','isp_user_priviledges.func_id','=','isp_functions.func_id','left')
            ->where('isp_user_priviledges.lvl_id','=',$lvl)
            ->where('isp_controllers.ctrl_url','=',$controller)
            ->where('isp_functions.func_url','=',$function)
            ->get();
        if ($data->count()==0){
            return false;
        } else {
            return $data->first();
        }
    }
}
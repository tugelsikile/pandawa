<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seedMenuToPrivileges extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_level = \App\UserLevel::all();
        foreach ($user_level as $item){
            $chk = \App\UserPriviledges::where('lvl_id',$item->lvl_id)->where('ctrl_id',14)->where('func_id',24)->get();
            if ($chk->count()===0){
                $priv = new \App\UserPriviledges();
                $priv->lvl_id   = $item->lvl_id;
                $priv->ctrl_id  = 14;
                $priv->func_id  = 24;
                $priv->R_opt = $priv->C_opt = $priv->U_opt = $priv->D_opt = 0;
                $priv->save();
            }
        }
    }
}

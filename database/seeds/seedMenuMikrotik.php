<?php

use Illuminate\Database\Seeder;

class seedMenuMikrotik extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = \App\Controller::where('ctrl_id',15)->get();
        if ($check->count()===0){
            $ctrl = new \App\Controller();
            $ctrl->ctrl_id = 15;
            $ctrl->ctrl_name    = 'Radius Server';
            $ctrl->ctrl_label   = 'Radius Server';
            $ctrl->description  = 'Data radius server';
            $ctrl->ctrl_icon    = '';
            $ctrl->ctrl_refs    = 'radius_server';
            $ctrl->ctrl_url     = 'radius_server';
            $ctrl->save();
            $check = \App\Functions::where('ctrl_id',$ctrl->ctrl_id)->where('func_id',25)->get();
            if ($check->count()===0){
                $func = new \App\Functions();
                $func->func_id  = 25;
                $func->ctrl_id  = 15;
                $func->func_name    = 'Radius Server';
                $func->func_label   = 'Radius Server';
                $func->description  = 'data radius server';
                $func->func_url     = 'index';
                $func->save();
            }
        }
    }
}

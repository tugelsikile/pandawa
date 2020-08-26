<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seedMenuJenisLayanan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = \App\Controller::where('ctrl_id',14)->get();
        if ($check->count()===0){
            $ctrl = new \App\Controller();
            $ctrl->ctrl_id = 14;
            $ctrl->ctrl_name = 'Data Jenis Layanan';
            $ctrl->ctrl_label = 'Data Jenis Layanan';
            $ctrl->description = 'Data Jenis Layanan';
            $ctrl->ctrl_icon = '';
            $ctrl->ctrl_refs = 'admin_customer';
            $ctrl->ctrl_url = 'admin_customer';
            $ctrl->save();
            $check = \App\Functions::where('ctrl_id',$ctrl->ctrl_id)->where('func_id',24)->get();
            if ($check->count()===0){
                $func = new \App\Functions();
                $func->func_id  = 24;
                $func->ctrl_id  = 14;
                $func->func_name    = 'Tabel Jenis Layanan Pelanggan';
                $func->func_label   = 'Tabel Jenis Layanan Pelanggan';
                $func->description  = 'Tabel Jenis Layanan Pelanggan';
                $func->func_url     = 'jenis-layanan';
                $func->save();
            }
        }
    }
}

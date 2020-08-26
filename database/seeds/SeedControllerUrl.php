<?php

use Illuminate\Database\Seeder;

class SeedControllerUrl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = DB::table('isp_controllers')->get();
        foreach ($data as $key => $val){
            DB::table('isp_controllers')->where('ctrl_id', $val->ctrl_id)->update(['ctrl_url' => str_replace('-','_',$val->ctrl_url)]);
        }
    }
}

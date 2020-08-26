<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class removeUnusedMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $delete = ['cabang-produk','cabang-customer','cabang-tagihan','cabang-account'];
        DB::table('isp_controllers')->whereIn('ctrl_url',$delete)->delete();
    }
}

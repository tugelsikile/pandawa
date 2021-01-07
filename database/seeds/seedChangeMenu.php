<?php

use Illuminate\Database\Seeder;

class seedChangeMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = \App\Controller::where(['ctrl_id'=>14])->get()->first();
        $data->ctrl_label = 'Jenis Layanan';
        $data->save();
    }
}

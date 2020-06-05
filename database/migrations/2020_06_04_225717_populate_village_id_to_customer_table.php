<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateVillageIdToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = \Illuminate\Support\Facades\DB::table('isp_customer')->select(['district_id','cust_id'])->get();
        foreach ($customers as $key => $customer){
            $village_id     = \App\Desa::where('district_id',$customer->district_id)->get()->first()->id;
            \Illuminate\Support\Facades\DB::table('isp_customer')->where('cust_id','=',$customer->cust_id)->update([
                'village_id' => $village_id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('isp_customer')->where('status','=',1)->update([
            'village_id' => null
        ]);
    }
}

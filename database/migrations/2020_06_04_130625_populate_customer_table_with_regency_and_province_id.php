<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateCustomerTableWithRegencyAndProvinceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = \Illuminate\Support\Facades\DB::table('isp_customer')->select(['district_id','cust_id'])->where(['status'=>1])->get();
        foreach ($customers as $key => $customer){
            $district       = \App\Kecamatan::where('id',$customer->district_id)->get()->first();
            $regency        = \App\Kabupaten::where('id',$district->regency_id)->get()->first();
            $province       = \App\Provinces::where('id',$regency->province_id)->get()->first();
            \Illuminate\Support\Facades\DB::table('isp_customer')->where('cust_id','=',$customer->cust_id)->update([
                'regency_id' => $regency->id,
                'province_id' => $province->id
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
            'regency_id' => null,
            'province_id' => null
        ]);
    }
}

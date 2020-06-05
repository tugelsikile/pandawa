<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulatePasVillageIdToCustomerTable extends Migration
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
            $village        = \App\Desa::where('district_id',$customer->district_id)->get()->first()->id;
            $district       = \App\Kecamatan::where('id',$customer->district_id)->get()->first();
            $regency        = \App\Kabupaten::where('id',$district->regency_id)->get()->first();
            $province       = \App\Provinces::where('id',$regency->province_id)->get()->first();
            \Illuminate\Support\Facades\DB::table('isp_customer')->where('cust_id','=',$customer->cust_id)->update([
                'pas_regency_id' => $regency->id,
                'pas_province_id' => $province->id,
                'pas_village_id' => $village
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
            'pas_regency_id' => null,
            'pas_province_id' => null,
            'pas_village_id' => null
        ]);
    }
}

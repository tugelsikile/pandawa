<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PopulatePacIdToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = \App\Customer::where(['status'=>1])->get();
        foreach ($customers as $key => $customer){
            $package = DB::table('isp_package_member')->where(['cust_id'=>$customer->cust_id,'status'=>1])->get()->first();
            if ($package){
                DB::table('isp_customer')->where(['cust_id'=>$customer->cust_id])->update(['pac_id'=>$package->pac_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('isp_customer')->update(['pac_id'=>null]);
    }
}

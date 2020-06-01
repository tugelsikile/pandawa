<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateCabIdInCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $cabangs = \App\Cabang::all();
        if (!collect($cabangs)->isEmpty()){
            foreach ($cabangs as $key => $cabang){
                $members = \App\CabangMember::where(['cab_id'=>$cabang->cab_id,'status'=>1])->get();
                if (!collect($members)->isEmpty()){
                    foreach ($members as $keyM => $member){
                        $customer = \App\Customer::where(['cust_id'=>$member->cust_id,'status'=>1])->get();
                        if (!collect($customer)->isEmpty()){
                            \App\Customer::where(['cust_id'=>$member->cust_id,'status'=>1])->update(['cab_id'=>$cabang->cab_id]);
                        }
                    }
                }
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
        DB::table('isp_customer')->update(array('cab_id' => null));
    }
}

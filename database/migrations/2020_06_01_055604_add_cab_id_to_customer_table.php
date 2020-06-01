<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCabIdToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('isp_customer')->where(['pas_date'=>'0000-00-00'])->update(array('pas_date' => null));
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->bigInteger('cab_id',false,true)->nullable();
            $table->foreign('cab_id')->on('isp_cabang')->references('cab_id')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->dropForeign(['isp_customer_cab_id_foreign']);
            $table->dropColumn('cab_id');
        });
    }
}

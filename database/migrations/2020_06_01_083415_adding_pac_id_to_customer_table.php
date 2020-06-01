<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingPacIdToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->bigInteger('pac_id',false,true)->nullable();
            $table->foreign('pac_id')->on('isp_package')->references('pac_id')->onDelete('cascade')->onUpdate('no action');
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
            $table->dropForeign(['isp_customer_pac_id_foreign']);
            $table->dropColumn('pac_id');
        });
    }
}

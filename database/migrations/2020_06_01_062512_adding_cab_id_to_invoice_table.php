<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingCabIdToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::table('isp_invoice')->where(['inv_date'=>'0000-00-00'])->update(array('inv_date' => null));
        DB::table('isp_invoice')->where(['due_date'=>'0000-00-00'])->update(array('due_date' => null));*/
        Schema::table('isp_invoice', function (Blueprint $table) {
            $table->bigInteger('cab_id',false,true)->nullable()->after('cust_id');
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
        Schema::table('isp_invoice', function (Blueprint $table) {
            $table->dropForeign(['isp_invoice_cab_id_foreign']);
            $table->dropColumn('cab_id');
        });
    }
}

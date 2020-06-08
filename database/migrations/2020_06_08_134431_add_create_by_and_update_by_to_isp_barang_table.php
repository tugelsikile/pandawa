<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreateByAndUpdateByToIspBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_barang', function (Blueprint $table) {
            $table->bigInteger('create_by',false,true)->nullable();
            $table->bigInteger('update_by',false,true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isp_barang', function (Blueprint $table) {
            $table->dropColumn('create_by');
            $table->dropColumn('update_by');
        });
    }
}

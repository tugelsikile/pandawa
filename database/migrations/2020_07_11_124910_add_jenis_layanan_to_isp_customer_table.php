<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisLayananToIspCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->bigInteger('jenis_layanan',false,true)->nullable();
            $table->foreign('jenis_layanan')->on('isp_jenis_layanan')->references('id')->onDelete('set null')->onUpdate('no action');
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
            $table->dropColumn('jenis_layanan');
        });
    }
}

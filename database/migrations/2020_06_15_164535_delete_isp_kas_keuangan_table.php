<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteIspKasKeuanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('isp_isp');
        Schema::dropIfExists('isp_isp_pages');
        Schema::dropIfExists('isp_isp_user_level');
        Schema::dropIfExists('isp_isp_user_priviledges');
        Schema::dropIfExists('isp_kas_keuangan');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('isp_kas_keuangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bulan',10)->default('01');
            $table->year('tahun')->default('2020');
            $table->bigInteger('saldo',false);
            $table->timestamps();
        });
    }
}

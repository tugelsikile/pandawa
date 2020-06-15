<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteIspKasKeuanganInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('isp_kas_keuangan_info');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('isp_kas_keuangan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('kas_id',false,true);
            $table->bigInteger('parent',false,false);
            $table->text('notes');
            $table->bigInteger('ammount',false,true)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->dateTime('tanggal')->nullable();
        });
    }
}

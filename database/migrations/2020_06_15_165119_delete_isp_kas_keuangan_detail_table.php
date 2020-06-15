<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteIspKasKeuanganDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('isp_kas_keuangan_detail');
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
            $table->bigInteger('parent',false)->nullable();
            $table->string('type')->default('masuk')->nullable();
            $table->string('name');
            $table->timestamps();
            $table->bigInteger('masuk',false,true)->nullable();
            $table->bigInteger('keluar',false,false)->nullable();
            $table->bigInteger('piutang',false,false)->nullable();
            $table->tinyInteger('is_link',4)->default(0)->nullable();
            $table->string('link_type')->nullable();
            $table->bigInteger('link_id',false,false);
        });
    }
}

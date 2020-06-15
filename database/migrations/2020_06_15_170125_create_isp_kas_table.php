<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIspKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isp_kas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bulan',2)->default(date('m'));
            $table->year('tahun')->default(date('Y'));
            $table->enum('kategori',['saldo awal','saldo akhir','pemasukan','pengeluaran','piutang'])->default('pemasukan');
            $table->bigInteger('ammount',false,false);
            $table->text('informasi')->nullable();
            $table->text('created_by')->nullable();
            $table->text('updated_by')->nullable();
            $table->bigInteger('priority',false,true)->default(2);
            $table->bigInteger('cab_id',false,false)->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isp_kas');
    }
}

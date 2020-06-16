<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIspKasRecursiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isp_kas_recursive', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('deskripsi')->nullable();
            $table->enum('kategori',['pemasukan','pengeluaran','piutang'])->default('pemasukan');
            $table->date('start_date')->default(date('Y-m-d'))->nullable();
            $table->date('end_date')->default(date('Y-m-d'))->nullable();
            $table->bigInteger('ammount',false,false);
            $table->enum('is_active',['aktif','non aktif'])->default('aktif');
            $table->text('created_by')->nullable();
            $table->text('updated_by')->nullable();
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
        Schema::dropIfExists('isp_kas_recursive');
    }
}

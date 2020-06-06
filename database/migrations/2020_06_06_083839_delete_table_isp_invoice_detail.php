<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteTableIspInvoiceDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('isp_invoice_detail');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('isp_invoice_detail',function ($table){
            $table->bigInteger('invd_id',true,true)->primary();
            $table->bigInteger('inv_id',false,true);
            $table->bigInteger('pac_id',false,true);
            $table->decimal('price',10,2);
            $table->tinyInteger('status',4)->default(1);
        });
    }
}

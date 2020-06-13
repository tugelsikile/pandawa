<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToIspFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_functions', function (Blueprint $table) {
            $table->bigInteger('ctrl_id',false,true)->change();
            $table->foreign('ctrl_id')->on('isp_controllers')->references('ctrl_id')->onDelete('cascade')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('isp_functions', function (Blueprint $table) {
            $table->dropForeign('isp_functions_ctrl_id_foreign');
            $table->bigInteger('ctrl_id',false,false)->change();
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

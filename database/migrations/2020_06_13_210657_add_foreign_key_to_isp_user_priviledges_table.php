<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToIspUserPriviledgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('isp_user_priviledges', function (Blueprint $table) {
            $table->bigInteger('lvl_id',false,true)->nullable()->change();
            $table->foreign('lvl_id')->on('isp_user_level')->references('lvl_id')->onDelete('cascade')->onUpdate('no action');
            $table->bigInteger('ctrl_id',false,true)->nullable()->change();
            $table->foreign('ctrl_id')->on('isp_controllers')->references('ctrl_id')->onDelete('cascade')->onUpdate('no action');
            $table->bigInteger('func_id',false,true)->nullable()->change();
            $table->foreign('func_id')->on('isp_functions')->references('func_id')->onDelete('cascade')->onUpdate('no action');
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('isp_user_priviledges', function (Blueprint $table) {
            $table->bigInteger('lvl_id',false,false)->nullable(false)->change();
            $table->dropForeign('isp_user_priviledges_lvl_id_foreign');
            $table->bigInteger('ctrl_id',false,false)->nullable(false)->change();
            $table->dropForeign('isp_user_priviledges_ctrl_id_foreign');
            $table->bigInteger('func_id',false,false)->nullable(false)->change();
            $table->dropForeign('isp_user_priviledges_func_id_foreign');
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

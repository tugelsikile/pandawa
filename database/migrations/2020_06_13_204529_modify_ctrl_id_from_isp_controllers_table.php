<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCtrlIdFromIspControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_controllers', function (Blueprint $table) {
            $table->bigInteger('ctrl_id',true,true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isp_controllers', function (Blueprint $table) {
            $table->integer('ctrl_id',true,true)->change();
        });
    }
}

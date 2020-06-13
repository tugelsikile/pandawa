<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToIspUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('isp_user', function (Blueprint $table) {
            $table->bigInteger('level',false,true)->default(null)->nullable()->change();
            $table->foreign('level')->on('isp_user_level')->references('lvl_id')->onDelete('set null')->onUpdate('no action');
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
        Schema::table('isp_user', function (Blueprint $table) {
            $table->dropForeign('isp_user_level_foreign');
            $table->integer('level',false,true)->default(1)->nullable()->change();
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

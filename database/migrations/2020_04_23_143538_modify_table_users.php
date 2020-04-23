<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_user', function (Blueprint $table) {
            $table->renameColumn('user_id','id');
            $table->renameColumn('user_name', 'email');
            $table->renameColumn('fullname','name');
            $table->dropColumn('created');
            $table->string('remember_token',255);
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
        Schema::table('isp_user', function (Blueprint $table) {
            $table->renameColumn('id','user_id');
            $table->renameColumn('email', 'user_name');
            $table->renameColumn('name','fullname');
            $table->dateTime('created');
            $table->dropColumn('remember_token');
            $table->dropTimestamps();
        });
    }
}

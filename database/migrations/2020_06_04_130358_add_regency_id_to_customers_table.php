<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegencyIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->char('regency_id',4)->nullable();
            $table->char('province_id',2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->dropColumn('regency_id');
            $table->dropColumn('province_id');
        });
    }
}

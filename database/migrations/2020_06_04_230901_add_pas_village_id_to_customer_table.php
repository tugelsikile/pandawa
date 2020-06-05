<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasVillageIdToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_customer', function (Blueprint $table) {
            $table->char('pas_village_id',10)->nullable();
            $table->char('pas_regency_id',4)->nullable();
            $table->char('pas_province_id',2)->nullable();
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
            $table->dropColumn('pas_village_id');
            $table->dropColumn('pas_regency_id');
            $table->dropColumn('pas_province_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegionsToIspSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isp_site', function (Blueprint $table) {
            $table->char('village_id',10)->nullable();
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
        Schema::table('isp_site', function (Blueprint $table) {
            $table->dropColumn('village_id');
            $table->dropColumn('regency_id');
            $table->dropColumn('province_id');
        });
    }
}

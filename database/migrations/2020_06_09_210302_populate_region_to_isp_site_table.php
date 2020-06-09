<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulateRegionToIspSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('isp_site')->update(['province_id'=>32,'regency_id'=>3212,'village_id'=>3212120001]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('isp_site')->update(['province_id'=>null,'regency_id'=>null,'village_id'=>null]);
    }
}

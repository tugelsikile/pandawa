<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PopulatePacIdToIspInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $invoices = \Illuminate\Support\Facades\DB::table('isp_invoice_detail')->where('status','=',1)->get();
        if(!is_null($invoices)){
            foreach ($invoices as $key => $invoice){
                \Illuminate\Support\Facades\DB::table('isp_invoice')->where('inv_id','=',$invoice->inv_id)->update(['pac_id'=>$invoice->pac_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('isp_invoice')->update(['pac_id'=>null]);
    }
}

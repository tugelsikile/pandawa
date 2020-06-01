<?php

namespace App\Repositories;

use App\Customer;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerRepositories{
    public function deletePackage(Request $request){
        try{
            foreach ($request->pac_id as $key => $pac){
                DB::table('isp_customer')->where(['pac_id'=>$pac,'status'=>1])->update(['pac_id'=>null]);
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProdukValidations{
    public function bulkDelete(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'pac_id' => 'required|array|min:1',
                'pac_id.*' => 'required|numeric|exists:isp_package,pac_id',
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matrix\Exception;

class ApiValidation{
    public function getToken(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'token' => 'required|string|exists:isp_api_token,token'
            ]);
            if ($valid->fails()){
                return format(500,'Invalid token');
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
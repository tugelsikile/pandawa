<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
    public function validCustomer(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'id' => 'required|string|exists:isp_customer,kode'
            ]);
            if ($valid->fails()){
                return format(500,'Data pelanggan tidak valid');
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function validInvoice(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'invoice' => 'required|string|exists:isp_invoice,inv_number'
            ]);
            if ($valid->fails()){
                return format(500,collect($valid->errors()->all())->join('#').'Data invoice tidak valid');
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
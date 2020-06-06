<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class TagihanValidation{
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[

            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function update(Request $request){
        try{
            $valid  = Validator::make($request->all(),[

            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function delete(Request $request){
        try{
            $valid  = Validator::make($request->all(),[

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
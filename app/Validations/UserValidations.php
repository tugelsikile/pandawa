<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UserValidations{
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'nama_cabang'       => 'required|numeric|exists:isp_cabang,cab_id',
                'nama_pengguna'     => 'required|string',
                'alamat_email'      => 'required|unique:isp_user,email,1,status',
                'kata_sandi'        => 'required|string|min:6',
                'ulangi_kata_sandi' => 'min:6|required_with:kata_sandi|same:kata_sandi',
                'level_pengguna'    => 'required|numeric|exists:isp_user_level,lvl_id'
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
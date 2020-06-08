<?php

namespace App\Validations;

use App\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mockery\Exception;

class UserValidations{
    public function delete(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'id' => 'required|numeric|exists:isp_user,id'
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
            $valid = Validator::make($request->all(),[
                'data_pengguna'     => 'required|numeric|exists:isp_user,id',
                'nama_cabang'       => 'sometimes|nullable|exists:isp_cabang,cab_id',
                'nama_pengguna'     => 'required|string',
                'alamat_email'      => [
                    'required',
                    'email',
                    Rule::unique('isp_user','email')->where('status','=',1)->ignore($request->data_pengguna,'id')
                ],
                'kata_sandi'        => 'sometimes|nullable|min:6',
                'ulangi_kata_sandi' => 'sometimes|nullable|min:6|required_if:kata_sandi,kata_sandi|same:kata_sandi',
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
    public function create(Request $request){
        try{
            if (strlen($request->nama_cabang)==0){
                $valid  = Validator::make($request->all(),[
                    'nama_pengguna'     => 'required|string',
                    'alamat_email'      => 'required|unique:isp_user,email,1,status',
                    'kata_sandi'        => 'required|string|min:6',
                    'ulangi_kata_sandi' => 'min:6|required_with:kata_sandi|same:kata_sandi',
                    'level_pengguna'    => 'required|numeric|exists:isp_user_level,lvl_id'
                ]);
            } else {
                $valid  = Validator::make($request->all(),[
                    'nama_cabang'       => 'exists:isp_cabang,cab_id',
                    'nama_pengguna'     => 'required|string',
                    'alamat_email'      => 'required|unique:isp_user,email,1,status',
                    'kata_sandi'        => 'required|string|min:6',
                    'ulangi_kata_sandi' => 'min:6|required_with:kata_sandi|same:kata_sandi',
                    'level_pengguna'    => 'required|numeric|exists:isp_user_level,lvl_id'
                ]);
            }
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
<?php
namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matrix\Exception;

class UserLevelValidation{
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'nama_hak_akses'    => 'required|string|min:5',
                'R_opt'             => 'sometimes|array',
                'C_opt'             => 'sometimes|array',
                'U_opt'             => 'sometimes|array',
                'D_opt'             => 'sometimes|array'
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
                'data_level_pengguna' => 'required|string|min:1|exists:isp_user_level,lvl_id',
                'nama_hak_akses'    => 'required|string|min:5',
                'R_opt'             => 'sometimes|array',
                'C_opt'             => 'sometimes|array',
                'U_opt'             => 'sometimes|array',
                'D_opt'             => 'sometimes|array'
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
                'id' => 'required|string|exists:isp_user_level,lvl_id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function deletePage(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'id' => 'required|string|exists:isp_functions,func_id',
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
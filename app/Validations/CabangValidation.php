<?php
namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CabangValidation{
    public function create(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'mitra' => 'required|string',
                'share_percent' => 'required|numeric|max:100|min:0',
                'cab_name' => [
                    'required', 'string',
                    Rule::unique('isp_cabang')->where(function ($query){
                        $query->where('status',1);
                    })
                ],
                //'cab_name' => 'required|string|unique:isp_cabang,cab_name,1,status',
                'alamat' => 'string',
                'village_id' => 'required|string|exists:isp_region_villages,id',
                'district_id' => 'required|string|exists:isp_region_districts,id',
                'kode_pos' => 'numeric',
                'telp' => 'required|string',
                'email' => 'email',
                'nama_pemilik' => 'required|string',
                'telp_pemilik' => 'string',
                'template' => 'required|string',
                'pad' => 'required|numeric|min:1|max:10'
            ]);
            if ($valid->fails()){
                $errors = collect($valid->errors()->all());
                throw new \Exception($errors->join('#'));
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $request;
    }
    public function update(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'cab_id' => 'required|string|exists:isp_cabang,cab_id',
                'mitra' => 'required|string',
                'share_percent' => 'required|numeric|max:100|min:0',
                'cab_name' => 'required|string|unique:App\Cabang,cab_name,'.$request->cab_id.',cab_id,status,1',
                'alamat' => 'string',
                'village_id' => 'required|string|exists:isp_region_villages,id',
                'district_id' => 'required|string|exists:isp_region_districts,id',
                'kode_pos' => 'numeric',
                'telp' => 'required|string',
                'email' => 'email',
                'nama_pemilik' => 'required|string',
                'telp_pemilik' => 'string',
                'template' => 'required|string',
                'pad' => 'required|numeric|min:1|max:10'
            ]);
            if ($valid->fails()){
                throw new \Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $request;
    }
    public function delete(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|exists:App\Cabang,cab_id'
            ]);
            if ($valid->fails()){
                throw new \Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $request;
    }
}
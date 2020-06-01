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
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'nama_cabang' => 'required|numeric|exists:isp_cabang,cab_id',
                'kode_produk' => 'required|string|unique:isp_package,kode',
                'nama_produk' => 'required|string|min:3',
                'keterangan_produk' => 'required|string',
                'kapasitas' => 'required|numeric',
                'besaran_kapasitas' => 'required|string',
                'harga_produk' => 'required|numeric',
                'pajak_produk' => 'required|numeric'
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
                'pac_id' => 'required|numeric|exists:isp_package,pac_id',
                'nama_cabang' => 'required|numeric|exists:isp_cabang,cab_id',
                'kode_produk' => 'required|string|unique:isp_package,kode,'.$request->pac_id.',pac_id,status,1',
                'nama_produk' => 'required|string|min:3',
                'keterangan_produk' => 'required|string',
                'kapasitas' => 'required|numeric',
                'besaran_kapasitas' => 'required|string',
                'harga_produk' => 'required|numeric',
                'pajak_produk' => 'required|numeric'
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
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|exists:isp_package,pac_id'
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
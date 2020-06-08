<?php

namespace App\Validations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class BarangValidation{
    public function create(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'nama_barang' => 'required|string|min:3',
                'mac_address' => 'sometimes|nullable|min:5',
                'tanggal_pembelian' => 'required|date_format:Y-m-d',
                'harga_pembelian' => 'sometimes|nullable|numeric|min:0',
                'harga_penjualan' => 'sometimes|nullable|numeric|min:0',
                'kondisi_barang' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat'
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
                'data_barang' => 'required|numeric|exists:isp_barang,br_id',
                'nama_barang' => 'required|string|min:3',
                'mac_address' => 'sometimes|nullable|min:5',
                'tanggal_pembelian' => 'required|date_format:Y-m-d',
                'harga_pembelian' => 'sometimes|nullable|numeric|min:0',
                'harga_penjualan' => 'sometimes|nullable|numeric|min:0',
                'kondisi_barang' => 'required|in:Baru,Baik,Rusak Ringan,Rusak Berat'
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
                'id' => 'required|numeric|exists:isp_barang,br_id'
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
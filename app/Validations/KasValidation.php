<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Matrix\Exception;

class KasValidation{
    public function delete(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'id' => 'required|numeric|exists:isp_kas,id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception( $exception->getMessage());
        }
        return $request;
    }
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'jenis_kas' => 'required|in:pengeluaran,pemasukan,piutang',
                'tanggal_kas' => 'required|date_format:Y-m-d',
                'nomor_bukti' => '',
                'uraian_kas' => 'required|string|min:10',
                'jumlah_kas' => 'required|numeric|min:0'
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
                'data_kas' => 'required|numeric|exists:isp_kas,id',
                'jenis_kas' => 'required|in:pengeluaran,pemasukan,piutang',
                'tanggal_kas' => 'required|date_format:Y-m-d',
                'nomor_bukti' => '',
                'uraian_kas' => 'required|string|min:10',
                'jumlah_kas' => 'required|numeric|min:0'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function updateRecursive(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'data_pengeluaran' => 'required|numeric|exists:isp_kas_recursive,id',
                'jenis_pengeluaran' => 'required|string|min:10',
                'tanggal_mulai_pengeluaran' => 'required|date_format:Y-m-d',
                'tanggal_akhir_pengeluaran' => 'sometimes',
                'jumlah_pengeluaran' => 'required|numeric|min:0'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function createRecursive(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'jenis_pengeluaran' => 'required|string|min:10',
                'tanggal_mulai_pengeluaran' => 'required|date_format:Y-m-d',
                'tanggal_akhir_pengeluaran' => 'sometimes',
                'jumlah_pengeluaran' => 'required|numeric|min:0'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function UpdateSaldoAwal(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'data_kas' => 'required|numeric|exists:isp_kas,id',
                'saldo_awal' => 'required|numeric',
                'kunci_saldo' => 'sometimes'
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
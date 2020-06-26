<?php

namespace App\Repositories;

use App\Kas;
use App\KasRecursive;
use Illuminate\Http\Request;
use Mockery\Exception;
use Illuminate\Support\Facades\DB;

class KasRecursiveRepository{
    public function table(Request $request){
        try{
            $data   = KasRecursive::all();
            $data->map(function ($data){
                $data->end_date === null ? $data->end_date = 'selamanya' : $data->end_date = $data->end_date;
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getBy($request){
        try{
            $data = KasRecursive::where($request)->get();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $data;
    }
    public function updateRecursive(Request $request){
        try{
            $data   = KasRecursive::where(['id'=>$request->data_pengeluaran])->get()->first();
            $data->deskripsi    = $request->jenis_pengeluaran;
            $data->kategori     = 'pengeluaran';
            $data->start_date   = $request->tanggal_mulai_pengeluaran;
            strlen($request->tanggal_akhir_pengeluaran) > 0 ? $data->end_date = $request->tanggal_akhir_pengeluaran : $data->end_date = null;
            $data->ammount      = $request->jumlah_pengeluaran;
            $data->updated_by   = auth()->user();
            $data->saveOrFail();
        }catch (\Matrix\Exception $exception){
            throw  new \Matrix\Exception($exception->getMessage());
        }
        return $request;
    }
    public function createRecursive(Request $request){
        try{
            $data   = new KasRecursive();
            $data->deskripsi    = $request->jenis_pengeluaran;
            $data->kategori     = 'pengeluaran';
            $data->start_date   = $request->tanggal_mulai_pengeluaran;
            strlen($request->tanggal_akhir_pengeluaran) > 0 ? $data->end_date = $request->tanggal_akhir_pengeluaran : $data->end_date = null;
            $data->ammount      = $request->jumlah_pengeluaran;
            $data->created_by   = auth()->user();
            $data->saveOrFail();
        }catch (\Matrix\Exception $exception){
            throw  new \Matrix\Exception($exception->getMessage());
        }
        return $request;
    }
    public function automate(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        try{
            $recursives = [];
            if ((int)$bulan <= (int)date('m') && (int)$tahun <= (int)date('Y')){
                $recursives = KasRecursive::where(['is_active'=>1])
                    ->whereMonth('start_date','<=',$bulan)
                    ->whereYear('start_date','<=',$tahun)
                    ->where(function ($q) use ($tahun,$bulan){
                        $q->whereMonth('end_date','>=',$bulan);
                        $q->orWhere(DB::raw("year(end_date)"), '>=', $tahun);
                        //$q->orWhereYear('end_date','>=',$tahun);
                        $q->orWhereNull('end_date');
                    })
                    ->where('kategori','=','pengeluaran')->get();
                if ($recursives->count()>0){
                    foreach ($recursives as $key => $recursive){
                        $data = Kas::where(['kategori'=>'pengeluaran','bulan'=>$bulan,'tahun'=>$tahun,'informasi'=>$recursive->deskripsi])->get();
                        if ($data->count()===0){
                            $data   = new Kas();
                            $data->bulan    = $request->bulan;
                            $data->tahun    = $request->tahun;
                            $data->kategori = 'pengeluaran';
                            $data->ammount  = $recursive->ammount;
                            $data->informasi= $recursive->deskripsi;
                            $data->created_by = 'automated';
                            $data->priority = 12;
                            $data->saveOrFail();
                        }
                    }
                }
            }
        }catch (\Matrix\Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request->merge(['pengeluaran'=>$recursives]);
    }
}
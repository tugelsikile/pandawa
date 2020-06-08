<?php

namespace App\Repositories;
use App\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class BarangRepositories{
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $data       = Barang::where(['status'=>1])
                ->where(function ($q) use ($keyword){
                    $q->where('kode','like',"%$keyword%");
                    $q->orWhere('nama_barang','like',"%$keyword%");
                })
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            $data->map(function ($data){
                $data->tgl_beli     = tglIndo($data->date_buy);
                $data->harga_beli   = format_rp($data->price_buy);
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        Log::channel('customLog')->info(['open'=>$request->url(),'user'=>Auth::user(),'params'=>$data]);
        return $data;
    }
    public function recordsFiltered(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $data       = Barang::where(['status'=>1])
                ->select('br_id')
                ->where(function ($q) use ($keyword){
                    $q->where('kode','like',"%$keyword%");
                    $q->orWhere('nama_barang','like',"%$keyword%");
                })->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsTotal(Request $request){
        try{
            $data       = Barang::where(['status'=>1])
                ->select('br_id')
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getByID($id){
        try{
            $data = Barang::where('br_id','=',$id)->get()->first();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){
        try{
            $data               = new Barang();
            $data->kode         = date('Ymd').str_pad(DB::table('isp_barang')->get()->count() + 1,4,'0',STR_PAD_LEFT);
            $data->nama_barang  = $request->nama_barang;
            $data->price_buy    = $request->harga_pembelian;
            $data->price_sell   = $request->harga_penjualan;
            $data->kondisi      = $request->kondisi_barang;
            $data->mac_address  = $request->mac_address;
            $data->date_buy     = $request->tanggal_pembelian;
            $data->create_by    = Auth::user()->id;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        Log::channel('customLog')->info(['open'=>$request->url(),'user'=>Auth::user(),'params'=>$data]);
        return $request;
    }
    public function update(Request $request){
        try{
            $data               = Barang::where('br_id','=',$request->data_barang)->get()->first();
            $data->nama_barang  = $request->nama_barang;
            $data->price_buy    = $request->harga_pembelian;
            $data->price_sell   = $request->harga_penjualan;
            $data->kondisi      = $request->kondisi_barang;
            $data->mac_address  = $request->mac_address;
            $data->date_buy     = $request->tanggal_pembelian;
            $data->update_by    = Auth::user()->id;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        Log::channel('customLog')->info(['open'=>$request->url(),'user'=>Auth::user(),'params'=>$data]);
        return $request;
    }
    public function delete(Request $request){
        try{
            $data               = Barang::where('br_id','=',$request->id)->get()->first();
            $data->status       = 0;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        Log::channel('customLog')->info(['open'=>$request->url(),'user'=>Auth::user(),'params'=>$data]);
        return $request;
    }
}
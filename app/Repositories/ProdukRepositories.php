<?php

namespace App\Repositories;


use App\Customer;
use App\Produk;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProdukRepositories{
    public function getCabangProduk(Request $request){
        try{
            if ($request->method()=='POST'){
                $data = Produk::where(['status'=>1,'cab_id'=>$request->cab_id])->orderBy('cap','asc')->orderBy('price','asc')->get();
                $data->map(function ($data){
                    $data->price_format = 'Rp. '.format_rp($data->price_with_tax);
                    return $data;
                });
            } else {
                $data = Produk::where(['status'=>1,'cab_id'=>$request->id])->orderBy('cap','asc')->orderBy('price','asc')->get();
                $data->map(function ($data){
                    $data->price_format = 'Rp. '.format_rp($data->price_with_tax);
                    return $data;
                });
            }
        }catch (\Mockery\Exception $exception){
            throw new \Mockery\Exception($exception->getMessage());
        }
        return $data;
    }
    public function getByID($pacID){
        try{
            $data = Produk::where(['pac_id'=>$pacID,'status'=>1])->get()->first();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function KodeProduk(Request $request){
        try{
            $cab_id = $request->cab_id;
            $data = Produk::where(['cab_id'=>$cab_id])->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $cab_id     = $request->post('cab_id');
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            $data   = Produk::where($where)
                ->where('pac_name','like',"%$keyword%")
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            $data->map(function ($data){
                $data->price = format_rp($data->price);
                $data->price_with_tax = format_rp($data->price_with_tax);
                $data->customers = Customer::where(['pac_id'=>$data->pac_id])->get()->count();
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' membaca data produk. ';
        Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
        return $data;
    }
    public function numRowsAll(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            $data = Produk::where($where)->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data->count();
    }
    public function numRows(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $keyword    = $request->post('search')['value'];
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            $data = Produk::where($where)
                ->where('pac_name','like',"%$keyword%")
                ->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data->count();
    }
    public function bulkDelete(Request $request){
        try{
            foreach ($request->pac_id as $key => $pac){
                $data = Produk::where(['pac_id'=>$pac])->get()->first();
                $data->status = 0;
                $data->save();
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus banyak data produk. ';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function create(Request $request){
        try{
            $data = new Produk();
            $data->kode     = $request->kode_produk;
            $data->pac_name = $request->nama_produk;
            $data->price    = $request->harga_produk;
            $data->description = $request->keterangan_produk;
            $data->cap      = $request->kapasitas;
            $data->cap_byte = $request->besaran_kapasitas;
            $data->tax_percent = $request->pajak_produk;
            $data->price_with_tax = ( $data->price * $data->tax_percent) / 100;
            $data->price_with_tax = $data->price_with_tax + $data->price;
            $data->cab_id   = $request->nama_cabang;
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menambahkan data produk. '.$data->pac_name;
        Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
        return $data;
    }
    public function update(Request $request){
        try{
            $data   = Produk::where(['pac_id'=>$request->pac_id])->get()->first();
            $data->kode     = $request->kode_produk;
            $data->pac_name = $request->nama_produk;
            $data->price    = $request->harga_produk;
            $data->description = $request->keterangan_produk;
            $data->cap      = $request->kapasitas;
            $data->cap_byte = $request->besaran_kapasitas;
            $data->tax_percent = $request->pajak_produk;
            $data->price_with_tax = ( $data->price * $data->tax_percent) / 100;
            $data->price_with_tax = $data->price_with_tax + $data->price;
            $data->cab_id   = $request->nama_cabang;
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' merubah data produk.';
        Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function delete(Request $request){
        try{
            $data   = Produk::where(['pac_id'=>$request->id])->get()->first();
            $data->status = 0;
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data produk.';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
}
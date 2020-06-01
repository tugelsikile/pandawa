<?php

namespace App\Repositories;


use App\Customer;
use App\Produk;
use Illuminate\Http\Request;
use Exception;

class ProdukRepositories{
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
        return $request;
    }
}
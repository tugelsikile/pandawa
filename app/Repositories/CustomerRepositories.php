<?php

namespace App\Repositories;

use App\Cabang;
use App\Customer;
use App\Invoice;
use App\Produk;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerRepositories{
    public function deletePackage(Request $request){
        try{
            foreach ($request->pac_id as $key => $pac){
                DB::table('isp_customer')->where(['pac_id'=>$pac,'status'=>1])->update(['pac_id'=>null]);
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function deletePackageID(Request $request){
        try{
            DB::table('isp_customer')->where(['pac_id'=>$request->id,'status'=>1])->update(['pac_id'=>null]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function recordsTotal(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $is_active  = $request->post('is_active');
            $npwp       = $request->post('npwp');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['is_active'] = $is_active;
            if (strlen($npwp)>0) $where['npwp'] = $npwp;
            $data = Customer::select('cust_id')
                ->where($where)
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsFiltered(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $cab_id     = $request->post('cab_id');
            $is_active  = $request->post('is_active');
            $npwp       = $request->post('npwp');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['is_active'] = $is_active;
            if (strlen($npwp)>0) $where['npwp'] = $npwp;
            $data = Customer::select('cust_id')
                ->where($where)
                ->where(function ($query) use ($keyword){
                    $query->where('kode','like',"%$keyword%");
                    $query->orWhere('fullname','like',"%$keyword%");
                })
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $cab_id     = $request->post('cab_id');
            $is_active  = $request->post('is_active');
            $npwp       = $request->post('npwp');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['is_active'] = $is_active;
            if (strlen($npwp)>0) $where['npwp'] = $npwp;
            $data = Customer::where($where)
                ->where(function ($query) use ($keyword){
                    $query->where('kode','like',"%$keyword%");
                    $query->orWhere('fullname','like',"%$keyword%");
                })
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            $curDate = date('Y-m-').'01';
            $data->map(function($data) use ($curDate){
                $data->cabang  = Cabang::where(['cab_id'=>$data->cab_id,'status'=>1])->get()->first();
                $data->invoice = Invoice::where(['cust_id'=>$data->cust_id,'status'=>1,'inv_date'=>$curDate])->get()->first();
                $data->package = Produk::where(['pac_id'=>$data->pac_id,'status'=>1])->get()->first();
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){

    }
    public function update(Request $request){

    }
    public function delete(Request $request){

    }
    public function bulkDelete(Request $request){

    }
}
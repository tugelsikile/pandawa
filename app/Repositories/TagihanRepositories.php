<?php

namespace App\Repositories;

use App\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class TagihanRepositories{
    public function minYear(Request $request){
        $year = date('Y');
        try{
            $year = date('Y',strtotime(Tagihan::select(DB::raw('MIN(inv_date) AS min_date'))->get()->first()->min_date));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $year;
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
            $npwp       = $request->post('npwp');
            $isActive   = $request->post('is_active');
            $invMonth   = $request->post('inv_month');
            $invYear    = $request->post('inv_year');
            $isPaid     = $request->post('inv_paid');

            $where      = [
                'isp_invoice.status' => 1,
                'isp_customer.status' => 1
            ];
            if (strlen($cab_id)>0) $where['isp_invoice.cab_id'] = $cab_id;
            if (strlen($isActive)>0) $where['isp_customer.is_active'] = $isActive;
            if (strlen($npwp)>0) $where['isp_customer.npwp'] = $npwp;
            if (strlen($isPaid)>0) $where['isp_invoice.is_paid'] = $isPaid;
            $data       = Tagihan::where($where)->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left');
            if (strlen($invMonth)>0) $data = $data->where(DB::raw('MONTH(isp_invoice.inv_date)'),$invMonth);
            if (strlen($invYear)>0) $data = $data->where(DB::raw('YEAR(isp_invoice.inv_date)'),$invYear);
            $data       = $data->where(function ($q) use ($keyword){
                    $q->where('isp_invoice.inv_number','like',"%$keyword%");
                    $q->orWhere('isp_customer.fullname','like',"%$keyword%");
                  })
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->select(['isp_invoice.*','isp_customer.fullname'])
                ->get();
            $data->map(function($data){
                $data->nomor = 0;
                $data->harga    = format_rp($data->price_with_tax);
                $data->periode  = bulanIndo(date('m',strtotime($data->inv_date))).' '.date('Y',strtotime($data->inv_date));
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsFiltered(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $cab_id     = $request->post('cab_id');
            $npwp       = $request->post('npwp');
            $isActive   = $request->post('is_active');
            $invMonth   = $request->post('inv_month');
            $invYear    = $request->post('inv_year');
            $isPaid     = $request->post('inv_paid');

            $where      = [
                'isp_invoice.status' => 1,
                'isp_customer.status' => 1
            ];
            if (strlen($cab_id)>0) $where['isp_invoice.cab_id'] = $cab_id;
            if (strlen($isActive)>0) $where['isp_customer.is_active'] = $isActive;
            if (strlen($npwp)>0) $where['isp_customer.npwp'] = $npwp;
            if (strlen($isPaid)>0) $where['isp_invoice.is_paid'] = $isPaid;
            $data       = Tagihan::where($where)->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left');
            if (strlen($invMonth)>0) $data = $data->where(DB::raw('MONTH(isp_invoice.inv_date)'),$invMonth);
            if (strlen($invYear)>0) $data = $data->where(DB::raw('YEAR(isp_invoice.inv_date)'),$invYear);
            $data       = $data->where(function ($q) use ($keyword){
                $q->where('isp_invoice.inv_number','like',"%$keyword%");
                $q->orWhere('isp_customer.fullname','like',"%$keyword%");
            })
                ->select(['isp_invoice.inv_id'])
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsTotal(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $npwp       = $request->post('npwp');
            $isActive   = $request->post('is_active');
            $invMonth   = $request->post('inv_month');
            $invYear    = $request->post('inv_year');
            $isPaid     = $request->post('inv_paid');

            $where      = [
                'isp_invoice.status' => 1,
                'isp_customer.status' => 1
            ];
            if (strlen($cab_id)>0) $where['isp_invoice.cab_id'] = $cab_id;
            if (strlen($isActive)>0) $where['isp_customer.is_active'] = $isActive;
            if (strlen($npwp)>0) $where['isp_customer.npwp'] = $npwp;
            if (strlen($isPaid)>0) $where['isp_invoice.is_paid'] = $isPaid;
            $data       = Tagihan::where($where)->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left');
            if (strlen($invMonth)>0) $data = $data->where(DB::raw('MONTH(isp_invoice.inv_date)'),$invMonth);
            if (strlen($invYear)>0) $data = $data->where(DB::raw('YEAR(isp_invoice.inv_date)'),$invYear);
            $data = $data->select(['isp_invoice.inv_id'])
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){
        try{

        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function update(Request $request){
        try{

        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function delete(Request $request){
        try{

        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
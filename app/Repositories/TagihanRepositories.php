<?php

namespace App\Repositories;

use App\Tagihan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Support\Facades\Log;

class TagihanRepositories{
    public function create(Request $request){
        try{
            $data                       = new Tagihan();
            $data->inv_number           = $request->npwp == 1 ? genInvNumber($request->bulan_tagihan,$request->tahun_tagihan,1) : genInvNumber($request->bulan_tagihan,$request->tahun_tagihan);
            $data->cust_id              = $request->nama_pelanggan;
            $data->cab_id               = $request->nama_cabang;
            $data->inv_date             = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-01';
            $data->due_date             = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-'.dueDate();
            $data->is_tax               = $request->npwp;
            $data->tax_percent          = $request->tax_percent;
            $data->price                = $request->price;
            $data->price_with_tax       = $request->price_with_tax;
            $data->price_tax            = $request->price_with_tax - $request->price;
            $data->pac_id               = $request->nama_produk;
            $data->delete_date          = null;
            $data->paid_date            = null;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menambahkan data tagihan manual.' . $data->inv_number;
        Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function GenInvoiceGetCustomer(Request $request){
        try{
            $data                   = new Tagihan();
            $data->inv_number       = $request->npwp == 1 ? genInvNumber($request->bulan_tagihan,$request->tahun_tagihan,1) : genInvNumber($request->bulan_tagihan,$request->tahun_tagihan);
            $data->cust_id          = $request->data_pelanggan;
            $data->cab_id           = $request->nama_cabang;
            $data->inv_date         = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-01';
            $data->due_date         = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-'.dueDate();
            $data->is_tax           = $request->npwp;
            $data->tax_percent      = $request->pajak_produk;
            $data->price            = $request->harga_produk;
            $data->price_tax        = $request->harga_produk_termasuk_pajak - $request->harga_produk;
            $data->price_with_tax   = $request->harga_produk_termasuk_pajak;
            $data->pac_id           = $request->nama_produk;
            $data->delete_date      = null;
            $data->paid_date        = null;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function minYear(Request $request){
        $year = date('Y');
        try{
            $year = date('Y',strtotime(Tagihan::select(DB::raw('MIN(inv_date) AS min_date'))->get()->first()->min_date));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $year;
    }
    public function CetakLaporan(Request $request){
        try{
            $where = ['isp_invoice.status'=>1];
            if (strlen($request->nama_cabang)>0) $where['isp_invoice.cab_id'] = $request->nama_cabang;
            if (strlen($request->npwp)>0) $where['isp_customer.npwp'] = $request->npwp;
            if (strlen($request->is_active)>0) $where['isp_customer.is_active'] = $request->is_active;
            if (strlen($request->status_bayar)>0) $where['is_paid'] = $request->status_bayar;
            $data = Tagihan::where($where)
                ->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left')
                ->select(['isp_customer.npwp','isp_customer.fullname','isp_invoice.inv_number','isp_invoice.inv_date','isp_invoice.price','isp_invoice.price_with_tax','isp_invoice.is_paid']);
            if (strlen($request->bulan_tagihan)>0) $data = $data->whereMonth('inv_date',$request->bulan_tagihan);
            if (strlen($request->tahun_tagihan)>0) $data = $data->whereYear('inv_date',$request->tahun_tagihan);
            $data = $data->orderBy('isp_invoice.inv_number','asc')->get()->chunk(20);
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
            if (strlen($invMonth)>0) $data = $data->whereMonth('isp_invoice.inv_date','=',$invMonth);
            if (strlen($invYear)>0) $data = $data->whereYear('isp_invoice.inv_date','=',$invYear);
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
                $data->paket    = $data->paketObj;
                $data->cabang   = $data->cabangObj;
                $data->harga    = format_rp($data->price_with_tax);
                if ($data->is_paid==0){
                    $data->tgl_bayar = null;
                } else {
                    $data->tgl_bayar = tglIndo(date('Y-m-d',strtotime($data->paid_date)));
                }
                $data->periode  = bulanIndo(date('m',strtotime($data->inv_date))).' '.date('Y',strtotime($data->inv_date));
                $data->makeHidden('paketObj');
                $data->makeHidden('cabangObj');
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' membaca data tagihan.';
        Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
        return $data;
    }
    public function getByID(Request $request){
        try{
            $data   = Tagihan::where('inv_id',$request->id)->get();
            $data->map(function ($data){
                $data->cabang   = $data->cabangObj;
                $data->customer = $data->customerObj;
                $data->paket    = $data->paketObj;
                $data->approved = User::where('id','=',$data->paid_approved_by)->get()->first();
                $data->makeHidden('cabangObj');
                $data->makeHidden('customerObj');
                $data->makeHidden('paketObj');
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data->first();
    }
    public function Cancel(Request $request){
        try{
            $data                       = Tagihan::where('inv_id',$request->data_tagihan)->get()->first();
            $data->is_paid              = 0;
            $data->paid_cancel_date     = $request->tanggal_pembatalan;
            $data->cancel_reason        = $request->keterangan_pembatalan;
            $data->cancel_by            = Auth::user()->id;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' mengcancel tagihan. '.$data->inv_number;
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function Approval(Request $request){
        try{
            $data                       = Tagihan::where('inv_id',$request->data_tagihan)->get()->first();
            $data->notes                = $request->keterangan_approval;
            $data->is_paid              = 1;
            $data->paid_date            = $request->tanggal_approval;
            $data->paid_approved_by     = Auth::user()->id;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' mengapprove tagihan. '.$data->inv_number;
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
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
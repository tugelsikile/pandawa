<?php

namespace App\Repositories;

use App\Cabang;
use App\Customer;
use App\Desa;
use App\Invoice;
use App\Produk;
use App\JenisLayanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class CustomerRepositories{
    public function deleteJenisLayanan(Request $request){
        try{
            $data = JenisLayanan::where(['id'=>$request->id])->delete();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function updateJenisLayanan(Request $request){
        try{
            $data = JenisLayanan::where('id','=',$request->data_jenis_layanan)->get()->first();
            $data->name = $request->nama_jenis_layanan;
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function createJenisLayanan(Request $request){
        try{
            $data   = new JenisLayanan();
            $data->name = $request->nama_jenis_layanan;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function getAllJenisLayanan(){
        try{
            $data = JenisLayanan::all();
            $data->map(function ($data){
                $data->count = $data->customer;
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getJenisLayanan($request){
        try{
            $data = JenisLayanan::where($request)->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getForGenerate(Request $request){
        try{
            $invDate = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-01';
            $data = Customer::where('status','=',1)->where('is_active','=',1)
                ->whereDate('from_date','<=',$invDate);
            if (strlen($request->nama_cabang)>0) $data->where('cab_id','=',$request->nama_cabang);
            $data = $data->get();
            $data->map(function ($data) use ($request){
                $data->bulan_tagihan = $request->bulan_tagihan;
                $data->tahun_tagihan = $request->tahun_tagihan;
                $data->paket = $data->paketObj;
                $data->cabang = $data->cabangObj;
                $data->makeHidden('cabangObj');
                $data->makeHidden('paketObj');
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getByID($id){
        try{
            $data   = Customer::where('cust_id',$id)->get()->first();
            $data->cabang   = $data->cabangObj;
            $data->desa     = $data->desa;
            $data->kecamatan= $data->kecamatan;
            $data->kabupaten= $data->kabupaten;
            $data->provinsi = $data->provinsi;
            $data->pasang_desa = $data->pasang_desa;
            $data->pasang_kecamatan = $data->pasang_kecamatan;
            $data->pasang_kabupaten = $data->pasang_kabupaten;
            $data->pasang_provinsi = $data->pasang_provinsi;
            $data->produk   = $data->produk;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function deletePackage(Request $request){
        try{
            foreach ($request->pac_id as $key => $pac){
                DB::table('isp_customer')->where(['pac_id'=>$pac,'status'=>1])->update(['pac_id'=>null]);
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data produk.';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function deletePackageID(Request $request){
        try{
            DB::table('isp_customer')->where(['pac_id'=>$request->id,'status'=>1])->update(['pac_id'=>null]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data produk.';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function recordsTotal(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $is_active  = $request->post('is_active');
            $npwp       = $request->post('npwp');
            $pac_id     = $request->post('pac_id');
            $date_range = $request->post('date_range');
            $min_date = $max_date = '';
            if (strlen($date_range)>0){
                $range = explode(' - ',$date_range);
                $min_date   = Carbon::createFromFormat('d/m/Y',$range[0])->format('Y-m-d');
                $max_date   = Carbon::createFromFormat('d/m/Y',$range[1])->format('Y-m-d');
            }
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['is_active'] = $is_active;
            if (strlen($npwp)>0) $where['npwp'] = $npwp;
            if (strlen($pac_id)>0) $where['pac_id'] = $pac_id;
            $data = Customer::select('cust_id')
                ->where($where);
            if (strlen($min_date)>0 && strlen($max_date)>0){
                $data = $data->whereBetween('from_date',[$min_date,$max_date])
                    ->orWhereBetween('pas_date',[$min_date,$max_date]);
            }
            $data = $data->get()->count();
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
            $pac_id     = $request->post('pac_id');
            $date_range = $request->post('date_range');
            $min_date = $max_date = '';
            if (strlen($date_range)>0){
                $range = explode(' - ',$date_range);
                $min_date   = Carbon::createFromFormat('d/m/Y',$range[0])->format('Y-m-d');
                $max_date   = Carbon::createFromFormat('d/m/Y',$range[1])->format('Y-m-d');
            }
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['is_active'] = $is_active;
            if (strlen($npwp)>0) $where['npwp'] = $npwp;
            if (strlen($pac_id)>0) $where['pac_id'] = $pac_id;
            $data = Customer::select('cust_id')
                ->where($where)
                ->where(function ($query) use ($keyword){
                    $query->where('kode','like',"%$keyword%");
                    $query->orWhere('fullname','like',"%$keyword%");
                });
            if (strlen($min_date)>0 && strlen($max_date)>0){
                $data = $data->whereBetween('from_date',[$min_date,$max_date])
                    ->orWhereBetween('pas_date',[$min_date,$max_date]);
            }
            $data = $data->get()->count();
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
            $mitra      = $request->post('mitra');
            $jenis      = $request->post('jenis_layanan');
            $pac_id     = $request->post('pac_id');
            $date_range = $request->post('date_range');
            $min_date = $max_date = '';
            if (strlen($date_range)>0){
                $range = explode(' - ',$date_range);
                $min_date   = Carbon::createFromFormat('d/m/Y',$range[0])->format('Y-m-d');
                $max_date   = Carbon::createFromFormat('d/m/Y',$range[1])->format('Y-m-d');
            }

            $where      = ['isp_customer.status'=>1];
            if (strlen($cab_id)>0) $where['isp_customer.cab_id'] = $cab_id;
            if (strlen($is_active)>0) $where['isp_customer.is_active'] = $is_active;
            if (strlen($npwp)>0) $where['isp_customer.npwp'] = $npwp;
            if (strlen($jenis)>0) $where['isp_customer.jenis_layanan'] = $jenis;
            $data = Customer::where($where)
                ->where(function ($query) use ($keyword){
                    $query->where('isp_customer.kode','like',"%$keyword%");
                    $query->orWhere('isp_customer.fullname','like',"%$keyword%");
                });
            if (strlen($min_date)>0 && strlen($max_date)>0){
                $data = $data->whereBetween('isp_customer.from_date',[$min_date,$max_date])
                    ->orWhereBetween('isp_customer.pas_date',[$min_date,$max_date]);
            }
            if (strlen($mitra)>0){
                $data = $data->join('isp_cabang','isp_customer.cab_id','=','isp_cabang.cab_id','left')->where('isp_cabang.mitra','=',$mitra);
            }
            if (strlen($pac_id)>0){
                $data = $data->where('isp_customer.pac_id',$pac_id);
            }
            $data = $data->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)->get();
            $curDate = date('Y-m-').'01';
            $data->map(function($data,$index) use ($curDate,$mitra){
                $data->cabang  = Cabang::where(['cab_id'=>$data->cab_id,'status'=>1])->get()->first();
                $data->invoice = Invoice::where(['cust_id'=>$data->cust_id,'status'=>1,'inv_date'=>$curDate])->get()->first();
                $data->package = Produk::where(['pac_id'=>$data->pac_id,'status'=>1])->get()->first();
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' membaca data customer';
        Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
        return $data;
    }
    public function PreviewID($cabID,$kecID=false,$kabID=false,$provID=false){
        try{
            $data = Customer::select('cust_id');
            if ($kecID || $kabID || $provID){
                if ($kecID) $data = $data->where('district_id','=',$kecID);
                if ($kabID) $data = $data->where('regency_id','=',$kabID);
                if ($provID) $data = $data->where('province_id','=',$provID);
            } else {
                $data = $data->where('cab_id','=',$cabID);
            }
            $data = $data->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){
        try{
            $data   = new Customer();
            $data->cab_id       = $request->nama_cabang;
            $data->kode         = $request->nomor_pelanggan;
            $data->npwp         = $request->punya_npwp;
            $data->npwp_nomor   = $request->nomor_npwp;
            $data->fullname     = $request->nama_pelanggan;
            $data->no_ktp       = $request->nomor_ktp;
            $data->address_01   = $request->alamat_perusahaan;
            $data->address_02   = Desa::find($request->nama_desa)->first()->name;
            $data->village_id   = $request->nama_desa;
            $data->district_id  = $request->nama_kecamatan;
            $data->regency_id   = $request->nama_kabupaten;
            $data->province_id  = $request->nama_provinsi;
            $data->postal_code  = $request->kode_pos;
            $data->phone        = $request->nomor_telp_pelanggan;
            $data->email        = $request->email_pelanggan;
            $data->penjab_name  = $request->nama_penanggunjawab;
            $data->penjab_jab   = $request->jabatan_penanggungjawab;
            $data->penjab_phone = $request->no_telp_penanggungjawab;
            $data->penjab_email = $request->email_penanggungjawab;
            $data->tech_name    = $request->nama_teknisi;
            $data->tech_jab     = $request->jabatan_teknisi;
            $data->tech_phone   = $request->no_telp_teknisi;
            $data->tech_email   = $request->email_teknisi;
            $data->order_num    = $request->nomor_order;
            $data->po_num       = $request->nomor_purchase_order;
            $data->quo_num      = $request->nomor_quotation;
            $data->finance_name = $request->nama_penanggungjawab_keuangan;
            $data->pas_address01= $request->alamat_penagihan;
            $data->pas_address02= Desa::find($request->desa_penagihan)->first()->name;
            $data->pas_village_id = $request->desa_penagihan;
            $data->pas_district_id= $request->kecamatan_penagihan;
            $data->pas_regency_id= $request->kabupaten_penagihan;
            $data->pas_province_id= $request->provinsi_penagihan;
            $data->pas_postal   = $request->kode_pos_penagihan;
            $data->pas_phone    = $request->no_telp_penagihan;
            $data->tagih_email  = $request->email_penagihan;
            $data->pac_id       = $request->nama_produk;
            $data->pas_ip       = implode(', ',$request->alamat_ip);
            $data->pas_promo    = $request->promosi;
            $data->paid_tipe    = $request->jenis_pembayaran;
            $data->pas_price    = $request->biaya_instalasi;
            $data->duration     = $request->durasi_berlangganan;
            $data->pas_date     = $request->tanggal_pemasangan;
            $data->from_date    = $request->tanggal_berlangganan;
            $data->jenis_layanan = $request->nama_jenis_layanan;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menambahkan data customer '.$data->fullname;
        Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function update(Request $request){
        try{
            $data               = Customer::where('cust_id',$request->data_pelanggan)->get()->first();
            $data->cab_id       = $request->nama_cabang;
            $data->npwp         = $request->punya_npwp;
            $data->npwp_nomor   = $request->nomor_npwp;
            $data->fullname     = $request->nama_pelanggan;
            $data->no_ktp       = $request->nomor_ktp;
            $data->address_01   = $request->alamat_perusahaan;
            $data->address_02   = Desa::find($request->nama_desa)->first()->name;
            $data->village_id   = $request->nama_desa;
            $data->district_id  = $request->nama_kecamatan;
            $data->regency_id   = $request->nama_kabupaten;
            $data->province_id  = $request->nama_provinsi;
            $data->postal_code  = $request->kode_pos;
            $data->phone        = $request->nomor_telp_pelanggan;
            $data->email        = $request->email_pelanggan;
            $data->penjab_name  = $request->nama_penanggunjawab;
            $data->penjab_jab   = $request->jabatan_penanggungjawab;
            $data->penjab_phone = $request->no_telp_penanggungjawab;
            $data->penjab_email = $request->email_penanggungjawab;
            $data->tech_name    = $request->nama_teknisi;
            $data->tech_jab     = $request->jabatan_teknisi;
            $data->tech_phone   = $request->no_telp_teknisi;
            $data->tech_email   = $request->email_teknisi;
            $data->order_num    = $request->nomor_order;
            $data->po_num       = $request->nomor_purchase_order;
            $data->quo_num      = $request->nomor_quotation;
            $data->finance_name = $request->nama_penanggungjawab_keuangan;
            $data->pas_address01= $request->alamat_penagihan;
            $data->pas_address02= Desa::find($request->desa_penagihan)->first()->name;
            $data->pas_village_id = $request->desa_penagihan;
            $data->pas_district_id= $request->kecamatan_penagihan;
            $data->pas_regency_id= $request->kabupaten_penagihan;
            $data->pas_province_id= $request->provinsi_penagihan;
            $data->pas_postal   = $request->kode_pos_penagihan;
            $data->pas_phone    = $request->no_telp_penagihan;
            $data->tagih_email  = $request->email_penagihan;
            $data->pac_id       = $request->nama_produk;
            $data->pas_ip       = implode(', ',$request->alamat_ip);
            $data->pas_promo    = $request->promosi;
            $data->paid_tipe    = $request->jenis_pembayaran;
            $data->pas_price    = $request->biaya_instalasi;
            $data->duration     = $request->durasi_berlangganan;
            $data->pas_date     = $request->tanggal_pemasangan;
            $data->from_date    = $request->tanggal_berlangganan;
            $data->jenis_layanan = $request->nama_jenis_layanan;
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' merubah data customer '.$data->fullname;
        Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function delete(Request $request){
        try{
            $data = Customer::where('cust_id',$request->id)->get()->first();
            $data->status = 0;
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data customer';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function bulkDelete(Request $request){
        try{
            foreach ($request->cust_id as $key => $item){
                $data = Customer::where('cust_id','=',$item)->get()->first();
                $data->status = 0;
                $data->save();
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus banyak data customer.';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function setStatus(Request $request){
        try{
            $data = Customer::where('cust_id','=',$request->id)->get()->first();
            $data->is_active = $request->data_status;
            if ($request->data_status == 1){
                $data->inactive_date = null;
            } else {
                $data->inactive_date = date('Y-m-d');
            }
            $data->save();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' mengupdate status customer';
        Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return $request;
    }
    public function CustomersCabang(Request $request){
        try{
            $data   = Customer::where('cab_id','=',$request->nama_cabang)
                ->where('status','=',1)
                ->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function grafikCustomer(Request $request){
        try{
            $data = Customer::where('status','=',1)->orderBy('from_date','asc')->get();
            if (strlen($request->cab_id)>0) $data = $data->where('cab_id','=',$request->cab_id);
            $total = $data->count();
            $data = $data->groupBy('from_date');
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return ['data'=>$data,'total'=>$total];
    }
}
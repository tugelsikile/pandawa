<?php

namespace App\Validations;

use App\Repositories\CustomerRepositories;
use App\Repositories\ProdukRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use App\Providers\ValidatorServiceProvider;

class TagihanValidation{
    protected $customerRepository;
    protected $produkRepository;
    public function __construct(
        ProdukRepositories $produkRepositories,
        CustomerRepositories $customerRepositories
    )
    {
        $this->produkRepository = $produkRepositories;
        $this->customerRepository = $customerRepositories;
    }

    public function GenInvoiceGetCustomer(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'data_pelanggan'        => 'required|string|exists:isp_customer,cust_id',
                'nama_cabang'           => 'required|string|exists:isp_cabang,cab_id',
                'nama_produk'           => 'required|string|exists:isp_package,pac_id',
                'bulan_tagihan'         => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
                'tahun_tagihan'         => 'required|numeric',
                'npwp'                  => 'required|in:1,0',
                'pajak_produk'          => 'required|numeric',
                'harga_produk'          => 'required|numeric',
                'harga_produk_termasuk_pajak'   => 'required|numeric'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'),403);
            }
            //check tagihan bulan ini
            $invDateFormat = $request->tahun_tagihan.'-'.$request->bulan_tagihan.'-01';
            $dataTagihan = DB::table('isp_invoice')
                ->select('inv_id')
                ->where([
                    'status'    => 1,
                    'cust_id'   => $request->data_pelanggan,
                ])
                ->where('inv_date','=',$invDateFormat)
                ->get();
            if ($dataTagihan->count()>0){
                throw new Exception('#Sudah ada invoice',403);
            }
            //check tagihan bulan ini
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function generate(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'bulan_tagihan' => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
                'tahun_tagihan' => 'required|digits:4'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function Cancel(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'data_tagihan'          => 'required|string|exists:isp_invoice,inv_id',
                'tanggal_pembatalan'    => 'required|date_format:Y-m-d',
                'keterangan_pembatalan' => 'required|string:min:5'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'),403);
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function Approval(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'data_tagihan'          => 'required|string|exists:isp_invoice,inv_id',
                'tanggal_approval'      => 'required|date_format:Y-m-d',
                'keterangan_approval'   => 'required|string|min:5'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function create(Request $request){
        try{
            $valid  = Validator::make($request->all(),[
                'nama_cabang'       => 'required|string|exists:isp_cabang,cab_id',
                'nama_pelanggan'    => 'required|string|exists:isp_customer,cust_id',
                'nama_produk'       => 'required|string|exists:isp_package,pac_id',
                'bulan_tagihan'     => 'required|in:01,02,03,04,05,06,07,08,09,10,11,12',
                'tahun_tagihan'     => 'required|min:4'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
            $customer   = $this->customerRepository->getByID($request->nama_pelanggan);
            $request    = $request->merge($customer->toArray());
            $produk     = $this->produkRepository->getByID($request->nama_produk);
            $request    = $request->merge($produk->toArray());
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function update(Request $request){
        try{
            $valid  = Validator::make($request->all(),[

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
            $valid  = Validator::make($request->all(),[

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
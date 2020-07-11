<?php

namespace App\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CustomerValidations{
    public function deleteJenisLayanan(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'id' => 'required|numeric|exists:isp_jenis_layanan,id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function updateJenisLayanan(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'data_jenis_layanan' => 'required|numeric|exists:isp_jenis_layanan,id',
                'nama_jenis_layanan' => 'required|string|unique:isp_jenis_layanan,name,'.$request->data_jenis_layanan.',id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function createJenisLayanan(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'nama_jenis_layanan' => 'required|string|unique:isp_jenis_layanan,name'
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
                'nama_cabang'           => 'required|string|exists:isp_cabang,cab_id',
                'nomor_pelanggan'       => 'required|string|unique:isp_customer,kode,1,status',
                'punya_npwp'            => 'required|numeric|in:1,0',
                'nomor_npwp'            => 'required_if:punya_npwp,==,1',
                'nama_pelanggan'        => 'required|string',
                'nomor_ktp'             => 'required|string',
                'alamat_perusahaan'     => 'required|string',
                'nama_desa'             => 'required|numeric|exists:isp_region_villages,id',
                'nama_kecamatan'        => 'required|numeric|exists:isp_region_districts,id',
                'nama_kabupaten'        => 'required|numeric|exists:isp_region_regencies,id',
                'nama_provinsi'         => 'required|numeric|exists:isp_region_provinces,id',
                'kode_pos'              => 'required|numeric',
                'nomor_telp_pelanggan'  => 'required|string',
                'alamat_penagihan'      => 'required|string',
                'desa_penagihan'        => 'required|numeric|exists:isp_region_villages,id',
                'kecamatan_penagihan'   => 'required|numeric|exists:isp_region_districts,id',
                'kabupaten_penagihan'   => 'required|numeric|exists:isp_region_regencies,id',
                'provinsi_penagihan'    => 'required|numeric|exists:isp_region_provinces,id',
                'kode_pos_penagihan'    => 'required|numeric',
                'nama_jenis_layanan'    => 'required|numeric|exists:isp_jenis_layanan,id',
                'nama_produk'           => 'required|string|exists:isp_package,pac_id',
                'alamat_ip'             => 'required|array|min:1',
                'alamat_ip.*'           => 'required|ip',
                'jenis_pembayaran'      => 'required|string|in:pre,post',
                'durasi_berlangganan'   => 'required|numeric|min:1|max:999999999',
                'tanggal_pemasangan'    => 'required|date_format:Y-m-d',
                'tanggal_berlangganan'  => 'required|date_format:Y-m-d|after:tanggal_pemasangan'
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
                'data_pelanggan'        => 'required|string|exists:isp_customer,cust_id',
                'nama_cabang'           => 'required|string|exists:isp_cabang,cab_id',
                'punya_npwp'            => 'required|numeric|in:1,0',
                'nomor_npwp'            => 'required_if:punya_npwp,==,1',
                'nama_pelanggan'        => 'required|string',
                'nomor_ktp'             => 'required|string',
                'alamat_perusahaan'     => 'required|string',
                'nama_desa'             => 'required|numeric|exists:isp_region_villages,id',
                'nama_kecamatan'        => 'required|numeric|exists:isp_region_districts,id',
                'nama_kabupaten'        => 'required|numeric|exists:isp_region_regencies,id',
                'nama_provinsi'         => 'required|numeric|exists:isp_region_provinces,id',
                'kode_pos'              => 'required|numeric',
                'nomor_telp_pelanggan'  => 'required|string',
                'alamat_penagihan'      => 'required|string',
                'desa_penagihan'        => 'required|numeric|exists:isp_region_villages,id',
                'kecamatan_penagihan'   => 'required|numeric|exists:isp_region_districts,id',
                'kabupaten_penagihan'   => 'required|numeric|exists:isp_region_regencies,id',
                'provinsi_penagihan'    => 'required|numeric|exists:isp_region_provinces,id',
                'kode_pos_penagihan'    => 'required|numeric',
                'nama_jenis_layanan'    => 'required|numeric|exists:isp_jenis_layanan,id',
                'nama_produk'           => 'required|string|exists:isp_package,pac_id',
                'alamat_ip'             => 'required|array|min:1',
                'alamat_ip.*'           => 'required|ip',
                'jenis_pembayaran'      => 'required|string|in:pre,post',
                'durasi_berlangganan'   => 'required|numeric|min:1|max:999999999',
                'tanggal_pemasangan'    => 'required|date_format:Y-m-d',
                'tanggal_berlangganan'  => 'required|date_format:Y-m-d|after:tanggal_pemasangan'
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
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|exists:isp_customer,cust_id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function bulkDelete(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'cust_id' => 'required|array|min:1',
                'cust_id.*' => 'required|string|exists:isp_customer,cust_id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function setStatus(Request $request){
        try{
            $valid = Validator::make($request->all(),[
                'id' => 'required|string|exists:isp_customer,cust_id',
                'data_status' => 'required|string'
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
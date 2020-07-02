<?php

namespace App\Http\Controllers;

use App\Repositories\CabangRepositories;
use App\Repositories\CustomerRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ListController extends Controller
{
    protected $customerRepository;
    protected $regionalRepository;
    protected $cabangRepository;

    public function __construct(
        CustomerRepositories $customerRepositories
    )
    {
        $this->customerRepository = $customerRepositories;
        $this->cabangRepository = new CabangRepositories();
    }

    public function ListCabang(Request $request){
        $data = [];
        try{
            $data = $this->cabangRepository->all($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$data);
    }

    public function CustomersCabang(Request $request){
        $data = [];
        try{
            $data   = $this->customerRepository->CustomersCabang($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$data);
    }
    public function Provinsi(Request $request){
        if (!$request->ajax()){ abort(403); } else {
            if ($request->method()!='POST'){ abort(403); } else {
                try{
                    $data = DB::table('isp_region_provinces')->where('id',$request->id)->get();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return $data;
            }
        }
    }
    public function Kabupaten(Request $request){
        if (!$request->ajax()){ abort(403); } else {
            if ($request->method()!='POST'){ abort(403); } else {
                try{
                    $where = ['province_id'=>$request->province_id];
                    if ($request->id) $where['id'] = $request->id;
                    $data = DB::table('isp_region_regencies')->where($where)->orderBy('name','asc')->get();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'OK',$data);
            }
        }
    }
    public function Kecamatan(Request $request){
        if (!$request->ajax()){ abort(403); } else {
            if ($request->method()!='POST'){ abort(403); } else {
                try{
                    $where = ['regency_id'=>$request->regency_id];
                    if ($request->id) $where['id'] = $request->id;
                    $data = DB::table('isp_region_districts')->orderBy('name','asc')->where($where)->get();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'OK',$data);
            }
        }
    }
    public function Desa(Request $request){
        if (!$request->ajax()){ abort(403); } else {
            if ($request->method()!='POST'){ abort(403); } else {
                try{
                    $where = ['district_id'=>$request->district_id];
                    if ($request->id) $where['id'] = $request->id;
                    $data = DB::table('isp_region_villages')->orderBy('name','asc')->where($where)->get();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'OK',$data);
            }
        }
    }
}

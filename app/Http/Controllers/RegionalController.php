<?php

namespace App\Http\Controllers;

use App\Repositories\RegionalRepositories;
use Illuminate\Http\Request;

class RegionalController extends Controller
{
    public $regional;

    public function __construct(
        RegionalRepositories $regionalRepositories
    )
    {
        $this->regional = $regionalRepositories;
    }
    public function kabupaten(Request $request){
        try{
            $data = $this->regional->getKab($request);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return ['code'=>1000,'msg'=>'OK','params'=>$data];
    }
    public function kecamatan(Request $request){
        try{
            $data = $this->regional->getKec($request);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return ['code'=>'1000','msg'=>'OK','params'=>$data];
    }
    public function desa(Request $request){
        try{
            $data = $this->regional->getDesa($request);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return ['code'=>1000,'msg'=>'OK','params'=>$data];
    }
}

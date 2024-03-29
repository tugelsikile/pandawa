<?php

namespace App\Repositories;

use App\Desa;
use App\Kabupaten;
use App\Kecamatan;
use App\Provinces;
use Illuminate\Http\Request;

class RegionalRepositories{
    public function getProv(Request $request){
        try{
            if ($request->method() == 'POST'){
                $data = Provinces::find($request->post('id'))->orderBy('name','asc');
                $data->map(function ($data){
                    $data->kab = $data->kabObj;
                    return $data;
                });
            } else {
                $data = Provinces::all();
                $data->map(function ($data){
                    $data->kab = $data->kabObj;
                    return $data;
                });
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getKab(Request $request){
        $data = [];
        try{
            if ($request->get('id')){
                $data   = Kabupaten::where('province_id',$request->get('id'))->orderBy('name','asc')->get();
                $data->map(function ($data){
                    $data->kecamatan    = $data->kecObj;
                    return $data;
                });
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getKec(Request $request){
        $data = [];
        try{
            if ($request->get('id')){
                $data   = Kecamatan::where('regency_id',$request->get('id'))->orderBy('name','asc')->get();
                $data->map(function ($data){
                    $data->desa     = $data->desaObj;
                    return $data;
                });
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getDesa(Request $request){
        $data = [];
        try{
            if ($request->get('id')){
                $data = Desa::where('district_id',$request->get('id'))->orderBy('name','asc')->get();
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getDesaByID($request){
        try{
            $data = Desa::where('id',$request)->get()->first();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getKecByID($request){
        try{
            $data = Kecamatan::where('id',$request)->get()->first();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getKabByID($request){
        try{
            $data = Kabupaten::where('id',$request)->get()->first();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
}
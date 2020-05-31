<?php

namespace App\Repositories;

use App\{
    Cabang, CabangMember, Desa
};
use Illuminate\Http\Request;
use Mockery\Exception;

class CabangRepositories{
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $data = Cabang::where('status','=',1)
                ->where('cab_name','like',"%$keyword%")
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            if (!is_null($data)){
                $data->map(function ($data){
                    $data->customer = CabangMember::where('status',1)->where('cab_id',$data->cab_id)->get();
                    return $data;
                });
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function numRows(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $data = Cabang::where('status','=',1)
                ->where('cab_name','like',"%$keyword%")
                ->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data->count();
    }
    public function create(Request $request){
        $data = new Cabang();
        try{
            $data->cab_name         = $request->cab_name;
            $data->id_template      = $request->template;
            $data->id_template_pad  = $request->pad;
            $data->address01        = ucwords(strtolower(Desa::where('id',$request->village_id)->get()->first()->name));
            $data->address02        = $request->alamat;
            $data->district_id      = $request->district_id;
            $data->village_id       = $request->village_id;
            $data->phone            = $request->telp;
            $data->email            = $request->email;
            $data->owner            = $request->nama_pemilik;
            $data->owner_phone      = $request->telp_pemilik;
            $data->postal           = $request->kode_pos;
            $data->mitra            = $request->mitra;
            $data->share_percent    = $request->share_percent;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function update(Request $request){
        try{
            $data   = Cabang::where('cab_id',$request->cab_id)->get()->first();
            $data->cab_name         = $request->cab_name;
            $data->id_template      = $request->template;
            $data->id_template_pad  = $request->pad;
            $data->address01        = ucwords(strtolower(Desa::where('id',$request->village_id)->get()->first()->name));
            $data->address02        = $request->alamat;
            $data->district_id      = $request->district_id;
            $data->village_id       = $request->village_id;
            $data->phone            = $request->telp;
            $data->email            = $request->email;
            $data->owner            = $request->nama_pemilik;
            $data->owner_phone      = $request->telp_pemilik;
            $data->postal           = $request->kode_pos;
            $data->mitra            = $request->mitra;
            $data->share_percent    = $request->share_percent;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function delete(Request $request){
        try{
            $data   = Cabang::where('cab_id',$request->id)->get()->first();
            $data->status = 0;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getByID($id){
        try{
            $data   = Cabang::find($id);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
}
<?php

namespace App\Repositories;

use App\{
    Cabang,
    CabangMember
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
}
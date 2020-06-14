<?php

namespace App\Repositories;

use App\Functions;
use Illuminate\Http\Request;
use Exception;

class FunctionRepository{
    public function getAll(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];

            $data   = Functions::select([
                'isp_controllers.ctrl_id','isp_controllers.ctrl_name','isp_controllers.ctrl_label','isp_controllers.ctrl_url',
                'isp_functions.func_id','isp_functions.func_name','isp_functions.func_label','isp_functions.func_url'
                ])
                ->join('isp_controllers','isp_functions.ctrl_id','=','isp_controllers.ctrl_id','left')
                ->where('isp_controllers.ctrl_name','like',"%$keyword%")
                ->orWhere('isp_controllers.ctrl_label','like',"%$keyword%")
                ->orWhere('isp_controllers.ctrl_url','like',"%$keyword%")
                ->orWhere('isp_functions.func_name','like',"%$keyword%")
                ->orWhere('isp_functions.func_label','like',"%$keyword%")
                ->orWhere('isp_functions.func_url','like',"%$keyword%")
                ->orderBy($orderby,$orderdir)
                ->limit($length,$start)
                ->get();
        }catch(Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function deletePage(Request $request){
        try{
            Functions::where('func_id','=',$request->id)->delete();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
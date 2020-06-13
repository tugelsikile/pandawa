<?php

namespace App\Repositories;

use App\Functions;
use App\UserLevel;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Http\Request;

class UserLevelRepositories{
    public function getBy($param){
        try{
            $data   = UserLevel::where($param)->get();
            $data->map(function ($data){
                $data->priviledges  = $data->userPriviledgesObj;
                return $data;
            });
        }catch (Exception $exception){
            return $exception->getMessage();
        }
        return $data;
    }
    public function getAll(){
        try{
            $data   = UserLevel::all();
            $data->map(function ($data){
                $data->priviledges  = $data->userPriviledgesObj;
                return $data;
            });
        }catch (Exception $exception){
            return $exception->getMessage();
        }
        return $data;
    }
    public function tableUserLevel(Request $request){
        try{
            $keyword = ''; $orderby = 'isp_invoice.inv_number'; $orderdir = 'asc';
            if (isset($request->search['value'])) $keyword = $request->search['value'];
            $start      = $request->start;
            $length     = $request->length;
            if (isset($request->order[0]['column'])){
                $orderby    = $request->order[0]['column'];
                $orderby    = $request->columns[$orderby]['data'];
                $orderdir   = $request->order[0]['dir'];
            }
            $data = UserLevel::where('lvl_name','like',"%$keyword%")
                ->orderBy($orderby,$orderdir)
                ->offset($start)
                ->limit($length)
                ->get();
            $data->map(function ($data){
                $data->users    = $data->userObj;
                $data->makeHidden('userObj');
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){
        try{
            dd(Functions::all()->with('controllerObj'));
            DB::beginTransaction();
            $data   = new UserLevel();
            $data->lvl_name = $request->nama_hak_akses;
            $data->saveOrFail();
            $lvl_id = $data->lvl_id;
            $functions = Functions::all();

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
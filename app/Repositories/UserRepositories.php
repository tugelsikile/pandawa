<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Illuminate\Http\Request;
use App\User;


class UserRepositories{
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $cab_id     = $request->post('cab_id');
            $level      = $request->post('level');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($level)>0) $where['level'] = $level;
            $data = User::where($where)
                ->where(function ($q) use ($keyword){
                    $q->where('name','like',"%$keyword%");
                    $q->orWhere('email','like',"%$keyword%");
                })
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            $data->map(function ($data){
                $data->cabang   = $data->cabangObj;
                $data->level    = $data->userLevelOjb;
                $data->makeHidden('cabangObj');
                $data->makeHidden('userLevelOjb');
                return $data;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsFiltered(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $cab_id     = $request->post('cab_id');
            $level      = $request->post('level');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($level)>0) $where['level'] = $level;
            $data = User::where($where)
                ->where(function ($q) use ($keyword){
                    $q->where('name','like',"%$keyword%");
                    $q->orWhere('email','like',"%$keyword%");
                })
                ->select('id')
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function recordsTotal(Request $request){
        try{
            $cab_id     = $request->post('cab_id');
            $level      = $request->post('level');
            $where      = ['status'=>1];
            if (strlen($cab_id)>0) $where['cab_id'] = $cab_id;
            if (strlen($level)>0) $where['level'] = $level;
            $data = User::where($where)
                ->select('id')
                ->get()->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function create(Request $request){
        try{
            $data               = new User();
            $data->cab_id       = $request->nama_cabang;
            $data->email        = $request->alamat_email;
            $data->name         = $request->nama_pengguna;
            $data->password     = Hash::make($request->kata_sandi);
            $data->level        = $request->level_pengguna;
            $data->remember_token = '';
            $data->saveOrFail();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
}
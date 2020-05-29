<?php

namespace App\Repositories;

use App\UserLevel;
use PHPUnit\Exception;

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
}
<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Exception;

class RadiusServerRepository{
    public function getUsers(Request $request){
        $data   = [];
        try{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET',config('app.MIX_API_RADIUS').'/api/user/list');
            $response = json_decode($response->getBody());
            if (isset($response->code)){
                if ($response->code === 1000){
                    return $response->params;
                }
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getUsersTable(Request $request){
        try{
            $keyword    = isset($request->search['value']) ? $request->search['value'] : null;
            $start      = $request->start;
            $length     = $request->length;
            $orderby    = isset($request->order[0]['column']) ? $request->order[0]['column'] : 0;
            $orderby    = isset($request->columns[$orderby]['data']) ? $request->columns[$orderby]['data'] : 'name';
            $orderdir   = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : 'asc';
            $cab_id     = $request->cab_id;
            $header = [
                'form_params' => [
                    'keyword' => $keyword,
                    'cab_id' => $cab_id,
                    'limit' => $length,
                    'offset' => $start,
                    'order_by' => $orderby,
                    'order_dir' => $orderdir,
                ]
            ];
            $client     = new \GuzzleHttp\Client();
            $response   = $client->request('POST',config('app.MIX_API_RADIUS').'/api/user/table',$header);
            $response   = json_decode($response->getBody());
            if (isset($response->code)){
                if ($response->code === 1000){
                    return $response->params;
                }
            }
            return [];
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function getAllUserLevel(){
        try{
            $client = new \GuzzleHttp\Client();
            $resp = $client->request('GET',config('app.MIX_API_RADIUS').'/api/user/level/list');
            $resp = json_decode($resp->getBody());
            if (isset($resp->code)){
                if ($resp->code === 1000){
                    return $resp->params;
                }
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function createUser(Request $request){
        try{
            $client = new \GuzzleHttp\Client();
            $header = [
                'form_params' => [
                    'cab_id' => $request->nama_cabang,
                    'name' => $request->nama_pengguna,
                    'email' => $request->alamat_email,
                    'password' => $request->kata_sandi,
                    'user_level' => $request->level_pengguna,
                ]
            ];
            $response = $client->request('POST',config('app.MIX_API_RADIUS').'/api/user/create',$header);
            $response = json_decode($response->getBody());
            return $response;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function updateUser(Request $request){
        try{
            $client = new \GuzzleHttp\Client();
            $header = [
                'form_params' => [
                    'id' => $request->id,
                    'cab_id' => $request->nama_cabang,
                    'name' => $request->nama_pengguna,
                    'email' => $request->alamat_email,
                    'password' => $request->kata_sandi,
                    'user_level' => $request->level_pengguna,
                ]
            ];
            $response = $client->request('POST',config('app.MIX_API_RADIUS').'/api/user/update',$header);
            $response = json_decode($response->getBody());
            return $response;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function deleteUser(Request $request){
        try{
            $client = new \GuzzleHttp\Client();
            $header = [
                'form_params' => [
                    'id' => $request->id,
                ]
            ];
            $response = $client->request('POST',config('app.MIX_API_RADIUS').'/api/user/delete',$header);
            $response = json_decode($response->getBody());
            return $response;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function getUserByID(Request $request){
        try{
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET',config('app.MIX_API_RADIUS').'/api/user/get?column=id&value='.$request->id);
            $response = json_decode($response->getBody());
            if (isset($response->code)){
                if ($response->code === 1000){
                    return $response->params;
                }
            }
            return [];
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
}
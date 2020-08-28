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
}
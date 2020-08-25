<?php

namespace App\Repositories;

use App\EmailSetting;
use App\EmailTemplate;
use Exception;

class MailRepository{
    public function getSetting(){
        try{
            $data   = EmailSetting::where('ms_id',1)->get()->first();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getTemplate($type){
        try{
            $data   = EmailTemplate::where('tmp_id',$type)->get()->first();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
}
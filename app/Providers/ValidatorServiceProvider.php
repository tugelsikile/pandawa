<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider{
    public function boot(){
        Validator::extend('wordsCount',function ($attribute,$value,$parameter,$validator){
            $words = explode(' ',$value);
            if (isset($parameter['min'])){
                $minimal = explode(':',$parameter['min']);
                if (count($words) < $minimal[1]) return false;
                return true;
            }
        });
    }
}
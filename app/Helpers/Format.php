<?php

function format($code,$msg,$params=false){
    return [
        'code'  => $code,
        'msg'   => $msg,
        'params'=> $params
    ];
}

function format_rp($ammount){
    return number_format($ammount,0,'','.');
}
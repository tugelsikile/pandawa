<?php

function format($code,$msg,$params=false){
    return [
        'code'  => $code,
        'msg'   => $msg,
        'params'=> $params
    ];
}
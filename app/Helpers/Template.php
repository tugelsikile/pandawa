<?php

function dataDesa($id){
    return \Illuminate\Support\Facades\DB::table('isp_region_villages')->where('id','=',$id)->get()->first();
}
function dataKec($id){
    return \Illuminate\Support\Facades\DB::table('isp_region_districts')->where('id','=',$id)->get()->first();
}
function dataKab($id){
    return \Illuminate\Support\Facades\DB::table('isp_region_regencies')->where('id','=',$id)->get()->first();
}
function dataProv($id){
    return \Illuminate\Support\Facades\DB::table('isp_region_provinces')->where('id','=',$id)->get()->first();
}

function appVersion(){
    $data = \Illuminate\Support\Facades\DB::table('migrations')->orderBy('id','desc')->get();
    if ($data->count()===0) {
        return 'alpha version';
    } else {
        $data   = $data->first();
        return $data->migration.'.bacth-'.$data->batch;
    }
}
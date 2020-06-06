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

function ArrayBulan(){
    return [
        [ 'value' => '01', 'name' => 'Januari' ],
        [ 'value' => '02', 'name' => 'Februari' ],
        [ 'value' => '03', 'name' => 'Maret' ],
        [ 'value' => '04', 'name' => 'April' ],
        [ 'value' => '05', 'name' => 'Mei' ],
        [ 'value' => '06', 'name' => 'Juni' ],
        [ 'value' => '07', 'name' => 'Juli' ],
        [ 'value' => '08', 'name' => 'Agustus' ],
        [ 'value' => '09', 'name' => 'September' ],
        [ 'value' => '10', 'name' => 'Oktober' ],
        [ 'value' => '11', 'name' => 'Nopember' ],
        [ 'value' => '12', 'name' => 'Desember' ],
    ];
}
function MinTahun(){
    $tahun = date('Y');
    try{
        $tahun = \Illuminate\Support\Facades\DB::table('isp_invoice')
        ->select(\Illuminate\Support\Facades\DB::raw('MIN(inv_date) AS min_date'))->get()->first()->min_date;
        $tahun = date('Y',strtotime($tahun));
    }catch (Exception $exception){
        throw new Exception($exception->getMessage());
    }
    return $tahun;
}
function hariIndo($date){ //date('N');
    $hariIndo 	= array("Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Minggu");
    return $hariIndo[(int)$date-1];
}
function bulanIndo($date){ //date('m');
    $BulanIndo	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
    return $BulanIndo[(int)$date-1];
}
function bulanIndoShort($date){ //date('m');
    $BulanIndo	= array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nop","Des");
    return $BulanIndo[(int)$date-1];
}
function tglIndo($date){
    if (!$date){
        $date	= date('Y-m-d');
    }
    $BulanIndo	= array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);

    $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
    return($result);
}
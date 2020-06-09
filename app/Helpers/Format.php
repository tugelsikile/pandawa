<?php

function sanitize($req){
    return collect($req->except('password','_token','columns','order','search.regex'))->toArray();
}

function companyInfo(){
    return \Illuminate\Support\Facades\DB::table('isp_site')
        ->select(['isp_site.*','isp_region_districts.name AS kec_name','isp_region_regencies.name AS kab_name','isp_region_provinces.name AS prov_name'])
        ->join('isp_region_districts','isp_site.district_id','=','isp_region_districts.id','left')
        ->join('isp_region_regencies','isp_region_districts.regency_id','=','isp_region_regencies.id','left')
        ->join('isp_region_provinces','isp_region_regencies.province_id','=','isp_region_provinces.id','left')
        ->get()->first();
}
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
function dueDate(){
    return \Illuminate\Support\Facades\DB::table('isp_site')->select('due_date')->get()->first()->due_date;
}
function templateInvoice($npwp=false){
    if (!$npwp){
        $data = \Illuminate\Support\Facades\DB::table('isp_template_id')->select(['id_string','str_pad'])->where('idnya','=',2)->get()->first();
    } else {
        $data = \Illuminate\Support\Facades\DB::table('isp_template_id')->select(['id_string','str_pad'])->where('idnya','=',1)->get()->first();
    }
    return ['template' => $data->id_string, 'padding' => $data->str_pad];
}
function genInvNumber($month,$year,$npwp=false){
    $template = templateInvoice($npwp);
    $stringInv  = $template['template'];
    $stringPad  = $template['padding'];
    $invDateFormat = $year.'-'.$month.'-01';
    $counter    = \Illuminate\Support\Facades\DB::table('isp_invoice')
        ->select('inv_id')
        ->where('inv_date','=',$invDateFormat)
        ->where('status','>',0);
    if ($npwp){
        $counter = $counter->where('is_tax','=',1);
    } else {
        $counter = $counter->where('is_tax','=',0);
    }
    $counter    = $counter->get()->count();
    $counter    = $counter + 1;
    $kodenya    = null;
    $explode    = explode('|',$stringInv);
    $register   = ['date','month','year','num','DATE','MONTH','YEAR'];
    foreach ($explode as $key => $string){
        $strpos     = strpos($string,'{');
        $kodenya    .= substr($string,0,$strpos);
        if (preg_match_all("/{(.*?)}/",$string,$m)){
            foreach ($m[1] as $i => $format){
                if (!in_array($format,$register)){
                    $kodenya .= $format;
                } else {
                    if ($format == 'date'){
                        $kodenya .= date('d');
                    } elseif ($format == 'month'){
                        $kodenya .= date('m');
                    } elseif ($format == 'year') {
                        $kodenya .= date('Y');
                    } elseif ($format == 'DATE'){
                        $kodenya .= romawi((int)date('d'));
                    } elseif ($format == 'MONTH'){
                        $kodenya .= romawi((int)date('m'));
                    } elseif ($format == 'YEAR'){
                        $kodenya .= romawi((int)date('Y'));
                    } else {
                        $kodenya .= str_pad($counter,$stringPad,'0',STR_PAD_LEFT);
                    }
                }
            }
        } else {
            $kodenya .= $string;
        }
    }
    return $kodenya;
}
function romawi($integer, $upcase = true) {
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0) {
        foreach($table as $rom=>$arb) {
            if($integer >= $arb) {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }
    return $return;
}
function terbilang($x) {
    $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    if ($x < 12)
        return " " . $angka[$x];
    elseif ($x < 20)
        return terbilang($x - 10) . " belas";
    elseif ($x < 100)
        return terbilang($x / 10) . " puluh" .terbilang($x % 10);
    elseif ($x < 200)
        return " seratus" . terbilang($x - 100);
    elseif ($x < 1000)
        return terbilang($x / 100) . " ratus" . terbilang($x % 100);
    elseif ($x < 2000)
        return " seribu" . terbilang($x - 1000);
    elseif ($x < 1000000)
        return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000)
        return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
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
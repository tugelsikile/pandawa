<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function PreviewHarga(Request $request){
        $harga  = $request->price;
        $tax    = $request->tax;
        $tax_price = ( $harga * $tax ) / 100;
        $tax_price = $tax_price + $harga;
        return format(1000,'OK','Rp. '.format_rp($tax_price));
    }
    public function PreviewID(Request $request){
        $template   = $request->post('template');
        $padding    = $request->post('padding');
        $kodenya    = null;
        $explode    = explode('|',$template);
        foreach ($explode as $string){
            $strpos     = strpos($string,'{');
            $kodenya    .= substr($string,0,$strpos);
            $register   = array('date','month','year','num','kab','kec','prov');
            if (preg_match_all("/{(.*?)}/", $string, $m)) {
                foreach ($m[1] as $i => $format) {
                    if (!in_array($format,$register)) {
                        $kodenya .= $format;
                    } else {
                        if ($format == 'date'){
                            $kodenya .= date('d');
                        } elseif ($format == 'month'){
                            $kodenya .= date('m');
                        } elseif ($format == 'year') {
                            $kodenya .= date('Y');
                        } elseif ($format == 'kab') {
                            $kodenya .= '31313';
                        } elseif ($format == 'kec') {
                            $kodenya .= '31313';
                        } elseif ($format == 'prov') {
                            $kodenya .= '31313';
                        } else {
                            $kodenya .= str_pad(1,$padding,'0',STR_PAD_LEFT);
                        }
                    }
                }
            } else {
                $kodenya .= $template;
            }
        }
        return format(1000,'OK',$kodenya);
    }
}

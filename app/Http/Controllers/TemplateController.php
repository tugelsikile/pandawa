<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Repositories\CabangRepositories;
use App\Repositories\RegionalRepositories;
use App\Repositories\CustomerRepositories;

class TemplateController extends Controller
{
    protected $cabangRepository;
    protected $customerRepository;
    protected $regional;

    public function __construct(
        CustomerRepositories $customerRepositories,
        RegionalRepositories $regionalRepositories,
        CabangRepositories $cabangRepositories
    )
    {
        $this->customerRepository = $customerRepositories;
        $this->cabangRepository = $cabangRepositories;
        $this->regional = $regionalRepositories;
    }

    public function PreviewHarga(Request $request){
        $harga  = $request->price;
        $tax    = $request->tax;
        $tax_price = ( $harga * $tax ) / 100;
        $tax_price = $tax_price + $harga;
        return format(1000,'OK','Rp. '.format_rp($tax_price));
    }
    public function PreviewCustomerID(Request $request){
        try{
            $data       = $this->cabangRepository->getByID($request->cab_id);
            $template   = $data->id_template;
            $padding    = $data->id_template_pad;
            $kodenya    = null;
            $explode    = explode('|',$template);
            $register   = ['date','month','year','num','kab','kec','prov'];
            $flagKec = $flagKab = $flagProv = null;
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
                            } elseif ($format == 'year'){
                                $kodenya .= date('Y');
                            } elseif ($format == 'kec'){
                                $kodenya .= $data->districts->id;
                                $flagKec = $data->districts->id;
                            } elseif ($format == 'kab'){
                                $kodenya .= $data->regencies->id;
                                $flagKab = $data->regencies->id;
                            } elseif ($format == 'prov'){
                                $kodenya .= $data->provinces->id;
                                $flagKec = $data->provinces->id;
                            } elseif ($format == 'num') {
                                $numeric = $this->customerRepository->PreviewID($request->cab_id,$flagKec,$flagKab,$flagProv);
                                $kodenya .= str_pad($numeric,$padding,'0',STR_PAD_LEFT);
                            }
                        }
                    }
                } else {
                    $kodenya .= $template;
                }
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$kodenya);
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

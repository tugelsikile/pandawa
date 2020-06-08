<?php

namespace App\Http\Controllers;

use App\Repositories\{ CustomerRepositories, CabangRepositories, TagihanRepositories, UserMenuRepositories, UserPriviledgesRepositories };
use App\Validations\TagihanValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class CabangTagihanController extends Controller
{
    protected $menuRepositories;
    protected $privileges;
    protected $tagihanValidation;
    protected $tagihanRepositories;
    protected $cabangRepositories;
    protected $customerRepositories;
    public $curMenu = 'cabang-tagihan';

    public function __construct(
        CustomerRepositories $customerRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories,
        TagihanRepositories $tagihanRepositories,
        TagihanValidation $tagihanValidation
    )
    {
        $this->menuRepositories = $userMenuRepositories;
        $this->privileges = $userPriviledgesRepositories;
        $this->cabangRepositories = $cabangRepositories;
        $this->tagihanRepositories = $tagihanRepositories;
        $this->tagihanValidation = $tagihanValidation;
        $this->customerRepositories = $customerRepositories;
    }

    public function index(Request $request){
        $curMenu = $this->curMenu;
        $privs = $this->privileges->checkPrivs(Auth::user()->level,$this->curMenu);
        $cabangs    = $this->cabangRepositories->all();
        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        $minTahun = $this->tagihanRepositories->minYear($request);
        return view('tagihan.index',compact('cabangs','curMenu','privs','menus','minTahun'));
    }
    public function table(Request $request){
        $response = ['draw'=>$request->draw,'data'=>[],'recordsFiltered'=>0,'recordsTotal'=>0];
        try{
            $response['data'] = $this->tagihanRepositories->table($request);
            $response['recordsFiltered'] = $this->tagihanRepositories->recordsFiltered($request);
            $response['recordsTotal'] = $this->tagihanRepositories->recordsTotal($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function FormGenerate(Request $request){
        if (!$request->ajax()){
            abort(403);
        } else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->generate($request);
                    $data   = $this->customerRepositories->getForGenerate($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'OK',$data);
            } else {
                $cabangs    = $this->cabangRepositories->all();
                return view('tagihan.generate-invoice',compact('cabangs'));
            }
        }
    }
    public function GenInvoiceGetCustomer(Request $request){
        try{
            $valid  = $this->tagihanValidation->GenInvoiceGetCustomer($request);
            $save   = $this->tagihanRepositories->GenInvoiceGetCustomer($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$save);
    }
    public function Cancel(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->Cancel($request);
                    $save   = $this->tagihanRepositories->Cancel($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan berhasil dibatalkan',$save);
            } else {
                try{
                    $data   = $this->tagihanRepositories->getByID($request);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.cancelation',compact('data'));
            }
        }
    }
    public function Approval(Request $request){
        if (!$request->ajax()){ abort(405); } else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->Approval($request);
                    $save   = $this->tagihanRepositories->Approval($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan berhasil disetujui',$save);
            } else {
                try{
                    $data   = $this->tagihanRepositories->getByID($request);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.approval',compact('data'));
            }
        }
    }
    public function create(Request $request){
        if (!$request->ajax()){ abort(405);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->create($request);
                    $save   = $this->tagihanRepositories->create($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan manual berhasil dibuat',$save);
            } else {
                try{
                    $cabangs    = $this->cabangRepositories->all();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.create',compact('cabangs'));
            }
        }
    }
    public function CetakLaporan(Request $request){
        //dd($request->bulan_tagihan);
        $data = [];
        try{
            $data   = $this->tagihanRepositories->CetakLaporan($request);
            $companyInfo = companyInfo();
            $judul_laporan = 'laporan ';
            strlen($request->bulan_tagihan)>0 ? $judul_laporan .= ' bulan '.bulanIndo($request->bulan_tagihan) : false;
            strlen($request->tahun_tagihan)>0 ? $judul_laporan .= ' tahun ' . $request->tahun_tagihan : false;
            strlen($request->nama_cabang)>0 ? $judul_laporan .= '<br>cabang '.$this->cabangRepositories->getByID($request->nama_cabang)->cab_name : false;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.cetak-laporan',compact('data','request','judul_laporan','companyInfo'));
    }
    public function CetakRekap(Request $request){
        $data = [];
        try{
            $data   = $this->tagihanRepositories->CetakLaporan($request);
            $companyInfo = companyInfo();
            $judul_laporan = 'laporan ';
            strlen($request->bulan_tagihan)>0 ? $judul_laporan .= ' bulan '.bulanIndo($request->bulan_tagihan) : false;
            strlen($request->tahun_tagihan)>0 ? $judul_laporan .= ' tahun ' . $request->tahun_tagihan : false;
            strlen($request->nama_cabang)>0 ? $judul_laporan .= '<br>cabang '.$this->cabangRepositories->getByID($request->nama_cabang)->cab_name : false;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.cetak-rekap',compact('data','request','judul_laporan','companyInfo'));
    }
    public function CetakInvoice(Request $request){
        if ($request->method()!='GET'){ abort(403); } else {
            $data = [];
            try{
                $companyInfo = companyInfo();
                $data   = $this->tagihanRepositories->getByID($request);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('tagihan.cetak-invoice',compact('data','companyInfo'));
        }
    }
}

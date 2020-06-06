<?php

namespace App\Http\Controllers;

use App\Repositories\{ CustomerRepositories, CabangRepositories, TagihanRepositories, UserMenuRepositories, UserPriviledgesRepositories };
use App\Validations\TagihanValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class TagihanController extends Controller
{
    protected $menuRepositories;
    protected $privileges;
    protected $tagihanValidation;
    protected $tagihanRepositories;
    protected $cabangRepositories;
    protected $customerRepositories;
    public $curMenu = 'admin-tagihan';

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
}
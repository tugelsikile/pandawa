<?php

namespace App\Http\Controllers;

use App\Provinces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories,
    UserPriviledgesRepositories,
    RegionalRepositories,
    CustomerRepositories,
    CabangRepositories
};
use App\Validations\CustomerValidations;
use Exception;

class CustomerController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $regional;
    protected $customerValidation;
    protected $customer;
    protected $cabang;
    public $curMenu = 'admin-customer';

    public function __construct(
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        RegionalRepositories $regionalRepositories,
        CustomerRepositories $customerRepositories,
        CustomerValidations $customerValidations,
        CabangRepositories $cabangRepositories
    )
    {
        $this->menuRepositories = $userMenuRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->regional = $regionalRepositories;
        $this->customer = $customerRepositories;
        $this->customerValidation = $customerValidations;
        $this->cabang = $cabangRepositories;
    }
    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        $cabangs = $this->cabang->all();
        return view('customer.index',compact('curMenu','menus','privs','cabangs'));
    }
    public function table(Request $request){
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $response['data'] = $this->customer->table($request);
            $response['recordsFiltered'] = $this->customer->recordsFiltered($request);
            $response['recordsTotal'] = $this->customer->recordsTotal($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->customerValidation->create($request);
                $save   = $this->customer->create($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Pelanggan berhasil ditambahkan',$save);
        } else {
            $cabangs = $this->cabang->all();
            $provs = Provinces::all();
            return view('customer.create',compact('provs','cabangs'));
        }
    }
    public function update(Request $request){

    }
    public function delete(Request $request){
    }
    public function bulkDelete(Request $request){

    }
}
